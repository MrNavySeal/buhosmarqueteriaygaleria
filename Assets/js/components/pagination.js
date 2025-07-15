import {common} from "./variables.js";
export default {
    template:`
        <div v-if="common.arrData.length > 0">
            <p class="text-center m-0 mb-1"><strong>Total de registros: </strong> {{common.intTotalResults}}</p>
            <p class="text-center m-0 mb-1">Página {{ common.intPage }} de {{ common.intTotalPages }}</p>
            <nav aria-label="Page navigation example" class="d-flex justify-content-center w-100">
                <ul class="pagination" id="pagination">
                    <li class="page-item" v-show="common.intPage > 1">
                        <a class="page-link text-secondary" href="#" @click="search(common.intPage = 1)" aria-label="Next">
                            <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
                        </a>
                    </li>
                    <li class="page-item" v-show="common.intPage > 1">
                        <a class="page-link text-secondary" href="#" @click="search(--common.intPage)" aria-label="Previous">
                            <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
                        </a>
                    </li>
                    <li v-for="(page,index) in common.arrButtons" :key="index"  @click="search(page)" class="page-item">
                        <a :class="common.intPage == page ?  'bg-primary text-white' : 'text-secondary'" class="page-link" href="#">{{page}}</a>
                    </li>
                    <li class="page-item" v-show="common.intPage < common.intTotalPages" @click="search(++common.intPage)">
                        <a class="page-link text-secondary" href="#" aria-label="Next">
                            <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
                        </a>
                    </li>
                    <li class="page-item" v-show="common.intPage < common.intTotalPages" @click="search(common.intPage = common.intTotalPages)">
                        <a class="page-link text-secondary" href="#" aria-label="Next">
                            <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    `,
    props:{
        common:common,
    },
    methods:{
        search:function(page){
            this.$emit("search",page);
        }
    }
}