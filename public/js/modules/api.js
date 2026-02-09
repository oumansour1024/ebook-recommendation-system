// api.js - Gestion des appels API
// Description: Ce fichier contient les fonctions pour interagir avec une API RESTful, y compris les méthodes CRUD, la gestion des erreurs, le caching, et les intercepteurs.

export function setupApi(baseUrl = '/api') {
    const api = {
        baseUrl,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        
        // Méthode générique pour les requêtes
        async request(endpoint, options = {}) {
            const url = `${this.baseUrl}${endpoint}`;
            const config = {
                method: 'GET',
                headers: { ...this.headers, ...options.headers },
                ...options
            };
            
            // Ajouter les credentials si nécessaire
            if (options.credentials !== false) {
                config.credentials = 'include';
            }
            
            try {
                const response = await fetch(url, config);
                
                // Vérifier si la réponse est OK
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Essayer de parser la réponse en JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return await response.json();
                } else {
                    return await response.text();
                }
                
            } catch (error) {
                console.error('API Request failed:', error);
                throw error;
            }
        },
        
        // Méthodes CRUD
        get(endpoint, options = {}) {
            return this.request(endpoint, { ...options, method: 'GET' });
        },
        
        post(endpoint, data, options = {}) {
            return this.request(endpoint, {
                ...options,
                method: 'POST',
                body: JSON.stringify(data)
            });
        },
        
        put(endpoint, data, options = {}) {
            return this.request(endpoint, {
                ...options,
                method: 'PUT',
                body: JSON.stringify(data)
            });
        },
        
        patch(endpoint, data, options = {}) {
            return this.request(endpoint, {
                ...options,
                method: 'PATCH',
                body: JSON.stringify(data)
            });
        },
        
        delete(endpoint, options = {}) {
            return this.request(endpoint, { ...options, method: 'DELETE' });
        },
        
        // Méthodes spécifiques
        async uploadFile(endpoint, file, options = {}) {
            const formData = new FormData();
            formData.append('file', file);
            
            return this.request(endpoint, {
                ...options,
                method: 'POST',
                headers: {}, // Ne pas définir Content-Type, laisse le navigateur le faire
                body: formData
            });
        },
        
        // Gestion du cache
        cache: new Map(),
        
        async getWithCache(endpoint, ttl = 300000) { // 5 minutes par défaut
            const now = Date.now();
            const cached = this.cache.get(endpoint);
            
            if (cached && (now - cached.timestamp) < ttl) {
                return cached.data;
            }
            
            const data = await this.get(endpoint);
            this.cache.set(endpoint, {
                data,
                timestamp: now
            });
            
            return data;
        },
        
        clearCache(endpoint = null) {
            if (endpoint) {
                this.cache.delete(endpoint);
            } else {
                this.cache.clear();
            }
        }
    };
    
    return api;
}

// Singleton API global
let globalApi = null;

export function getApi() {
    if (!globalApi) {
        globalApi = setupApi();
    }
    return globalApi;
}

// Intercepteurs (middleware pattern)
export function createApiWithInterceptors(interceptors = {}) {
    const api = setupApi();
    
    // Intercepteur de requête
    if (interceptors.request) {
        const originalRequest = api.request;
        api.request = async function(endpoint, options) {
            const modifiedOptions = await interceptors.request(endpoint, options);
            return originalRequest.call(this, endpoint, modifiedOptions);
        };
    }
    
    // Intercepteur de réponse
    if (interceptors.response) {
        const originalRequest = api.request;
        api.request = async function(endpoint, options) {
            try {
                const response = await originalRequest.call(this, endpoint, options);
                return await interceptors.response(response, endpoint, options);
            } catch (error) {
                if (interceptors.error) {
                    return await interceptors.error(error, endpoint, options);
                }
                throw error;
            }
        };
    }
    
    return api;
}

// Exemple d'intercepteur d'authentification
export function createAuthInterceptor(tokenGetter) {
    return {
        request: async (endpoint, options) => {
            const token = await tokenGetter();
            if (token) {
                options.headers = {
                    ...options.headers,
                    'Authorization': `Bearer ${token}`
                };
            }
            return options;
        },
        
        response: async (response) => {
            // Vérifier si le token a expiré
            if (response.status === 401) {
                // Gérer le rafraîchissement du token ici
                console.warn('Token expiré, redirection vers la page de connexion...');
                // window.location.href = '/login';
            }
            return response;
        },
        
        error: async (error) => {
            console.error('Erreur API:', error);
            throw error;
        }
    };
}

// Fonction utilitaire pour les requêtes parallèles
export function parallelRequests(requests) {
    return Promise.all(requests);
}

// Fonction utilitaire pour les requêtes séquentielles
export function sequentialRequests(requests) {
    return requests.reduce((promiseChain, request) => {
        return promiseChain.then(chainResults =>
            request().then(currentResult => [...chainResults, currentResult])
        );
    }, Promise.resolve([]));
}

// Gestion des erreurs API standardisées
export class ApiError extends Error {
    constructor(message, status, data = null) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.data = data;
        this.timestamp = new Date().toISOString();
    }
    
    toJSON() {
        return {
            name: this.name,
            message: this.message,
            status: this.status,
            data: this.data,
            timestamp: this.timestamp
        };
    }
}

// Validation des schémas de réponse
export function validateResponse(schema, data) {
    // Implémentation simple - vous pourriez utiliser une bibliothèque comme Zod ou Yup
    if (typeof schema === 'function') {
        return schema(data);
    }
    
    // Pour les besoins de base, vérifier le type
    if (schema === 'array' && !Array.isArray(data)) {
        throw new ApiError('Invalid response type: expected array', 500, data);
    }
    
    if (schema === 'object' && (typeof data !== 'object' || Array.isArray(data))) {
        throw new ApiError('Invalid response type: expected object', 500, data);
    }
    
    return data;
}
