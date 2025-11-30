const selectSort = document.querySelector("#selectSort");
const selectPerPage = document.querySelector("#selectPerPage");


window.addEventListener("load",function(){
    getData();
})

selectSort.addEventListener("change",function(){
    getData();
});

selectPerPage.addEventListener("change",function(){
    getData();
});

async function getData(page = 1){
    const routeCategory = document.querySelector("#routeCategory").value;
    const routeSubcategory = document.querySelector("#routeSubcategory").value;
    const strSearch = document.querySelector("#productSearch").value;
    const formData = new FormData();
    formData.append("page",page);
    formData.append("sort",selectSort.value);
    formData.append("per_page",selectPerPage.value);
    formData.append("category",routeCategory);
    formData.append("subcategory",routeSubcategory);
    formData.append("search",strSearch);

    document.querySelector("#divLoading").classList.remove("d-none");
    const response = await fetch(base_url+"/Tienda/getProducts",{method:"POST",body:formData});
    const objData = await response.json();
    document.querySelector("#divLoading").classList.add("d-none");

    document.querySelector("#filterResults").innerHTML = objData.html_total;
    document.querySelector("#productItems").innerHTML = objData.html;
    document.querySelector(".pagination").innerHTML = objData.html_pages;
}