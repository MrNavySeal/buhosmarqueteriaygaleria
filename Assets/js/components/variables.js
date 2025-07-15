
export const common = {
    //Common variables
    strSearch: "",
    intPerPage: 10,
    strInitialDate: new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0],
    strFinalDate: new Date().toISOString().split("T")[0],
    strName:"",
    intId:0,
    processing:false,

    //Pagination
    intPage:1,
    intStartPage:1,
    intTotalPages:1,
    intTotalButtons:1,
    intPerPage:10,
    intTotalResults:0,
    arrData:[],
    arrButtons:[],

    /**Modals**/
    //Titles
    modulesTitle:"Nuevo módulo",
    
    //Show Modals
    showModalModule:false
};