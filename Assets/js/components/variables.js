
export const common = {
    //Common variables
    strSearch: "",
    intPerPage: 10,
    strInitialDate: new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0],
    strFinalDate: new Date().toISOString().split("T")[0],
    strName:"",
    intStatus:1,
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
    modalType:"",
    
    //Titles
    modulesTitle:"Nuevo módulo",
    sectionTitle:"Nueva sección",
    categoryTitle:"Nueva categoría",
    subcategoryTitle:"Nueva subcategoría",
    
    //Show Modals
    showModal:"",
    showModalModule:false,
    showModalCategory:false,
    showModalSubcategory:false,
    showModalPaginationCategory:false,
};
export const btnProps = {
    btn:{
        type:String,
        default:"primary"
    },
    icon:{
        type:String,
        default:"",
    },
    type:{
        type:String,
        default:"button",
    },
    processing:{
        type:Boolean,
        default:false
    },
    title:{
        type:String,
        default:"",
    }
}

export function createCommon() {
    //Common variables
    return {
        strSearch: "",
        intPerPage: 10,
        strInitialDate: new Date(new Date().getFullYear(), 0, 1).toISOString().split("T")[0],
        strFinalDate: new Date().toISOString().split("T")[0],
        strName:"",
        intId:0,
        processing:false,
        errors:[],

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
        modalType:"",
        
        //Titles
        modulesTitle:"Nuevo módulo",
        sectionTitle:"Nueva sección",
        categoryTitle:"Nueva categoría",
        subcategoryTitle:"Nueva subcategoría",
        productTitle:"Nuevo producto",
        title:"",
        
        //Show Modals
        showModal:false,
        showModalModule:false,
        showModalCategory:false,
        showModalSubcategory:false,
        showModalProduct:false,
        showModalViewProduct:false,
        
        //Pagination modals
        showModalPaginationCategory:false,
        showModalPaginationSubcategory:false,
    }
};