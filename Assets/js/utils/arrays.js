/**
    * Filtra un array de objetos según un término de búsqueda en múltiples propiedades.
    * @param {Array} array - Array original a filtrar.
    * @param {string} searchTerm - Término de búsqueda.
    * @param {Array} searchFields - Campos del objeto donde buscar (opcional, si no se proporciona, busca en todas las propiedades string).
    * @param {boolean} caseSensitive - Si la búsqueda debe ser sensible a mayúsculas (default: false).
    * @returns {Array} Nuevo array con los elementos que coinciden con la búsqueda.
*/
export function filterArray(array, searchTerm, searchFields = [], caseSensitive = false) {
    if (!array || !searchTerm) return array || [];
    
    const term = caseSensitive ? searchTerm : searchTerm.toLowerCase();
    const fieldsToSearch = searchFields.length > 0 ? searchFields : Object.keys(array[0] || {});
    
    return array.filter(item => {
        return fieldsToSearch.some(field => {
            if (!item[field]) return false;
            
            const fieldValue = typeof item[field] === 'string' 
                ? (caseSensitive ? item[field] : item[field].toLowerCase())
                : String(item[field]);
                
            return fieldValue.includes(term);
        });
    });
}