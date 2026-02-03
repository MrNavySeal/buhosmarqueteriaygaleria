export function formatNumber(valor){
    valor = new String(valor);
    const [integerPart, decimalPart] = valor.split(".");

    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return decimalPart !== undefined
    ? `$${formattedInteger}.${decimalPart}`
    : `$${formattedInteger}`;
}

export function cleanFormatNumber(valor){
    valor = valor.replace(/[^0-9.]/g, "");
    return valor;
}