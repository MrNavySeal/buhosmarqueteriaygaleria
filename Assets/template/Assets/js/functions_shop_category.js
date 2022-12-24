window.addEventListener("load",function(){
    let selectSort = document.querySelector("#selectSort");
    let filter = document.querySelector("#filter");
    let filterOptions = document.querySelector(".filter-options");
    let filterOverlay = document.querySelector(".filter-options-overlay");
    let urlSearch = window.location.search;
    let params = new URLSearchParams(urlSearch);

    if(params.get("s")){
        let sortValue = parseInt(params.get("s"));
        if(sortValue - 1 > selectSort.options.length)sortValue=1;
        selectSort.options[sortValue-1].setAttribute("selected","selected");
    }
    
    filterOverlay.addEventListener("click",function(){
        filterOverlay.style.display="none";
        filterOptions.classList.remove("active");
    });
    filter.addEventListener("click",function(){
        filterOptions.classList.add("active");
        document.querySelector(".filter-options-overlay").style.display="block";
    });
    selectSort.addEventListener("change",function(){
        window.location.href = selectSort.options[selectSort.options.selectedIndex].getAttribute("data-url")+"&s="+selectSort.value;
    });
})