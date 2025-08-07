import { btnProps } from "./variables.js";
export default {
    template:`
        <button :class="{ 
            'btn btn-primary': btn=='primary',
            'btn btn-secondary': btn=='secondary',
            'btn btn-info text-white': btn=='info',
            'btn btn-warning': btn=='warning',
            'btn btn-danger': btn=='danger',
            'btn btn-success': btn=='success'
        }" :disabled = processing :type="type"
            :title = title
        >
            <div v-if="!processing" class="d-flex align-items-center gap-2">
                {{title}}
                <div v-show="icon!=''">
                    <i v-show="icon == 'back'" class="fas fa-reply"></i>
                    <i v-show="icon == 'search'" class="fas fa-search"></i>
                    <i v-show="icon == 'duplicate_window'" class="fas fa-window-restore"></i>
                    <i v-show="icon == 'new'" class="fas fa-plus"></i>
                    <i v-show="icon == 'save'" class="fas fa-save"></i>
                    <i v-show="icon == 'watch'" class="fas fa-eye"></i>
                    <i v-show="icon == 'edit'" class="fas fa-pencil-alt"></i>
                    <i v-show="icon == 'delete'" class="fas fa-trash-alt"></i>
                    <i v-show="icon == 'key'" class="fas fa-key"></i>
                    <i v-show="icon == 'box'" class="fas fa-box"></i>
                    <i v-show="icon == 'globe'" class="fas fa-globe"></i>
                    <i v-show="icon == 'list'" class="fa fa-list"></i>
                </div>
            </div> 
            <div v-if="processing"><span  class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>
        </button>
    `,
    props:btnProps,
}