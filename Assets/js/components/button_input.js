import AppButton from "./button.js"
import {btnProps} from "./variables.js";
export default {
    components:{
        "app-button":AppButton,
    },
    template:`
        <div class="mb-3">
            <div class="input-group">
                <app-button :icon="icon" :btn="btn"></app-button>
                <input type="text" class="form-control" :value="value" disabled aria-label="Example text with button addon" aria-describedby="button-addon1">
            </div>
            <ul>
                <li class="text-danger" v-for="(data,index) in errors">{{data}}<br></li>
            </ul>
        </div>
    `,
    props:{
        ...btnProps,
        errors:{
            type:Array,
            default:[]
        },
        value:""
    }
}