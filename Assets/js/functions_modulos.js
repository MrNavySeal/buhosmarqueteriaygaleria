import AppButton from "./components/button.js"; 
import AppModal from "./components/modal.js"; 
const App = {
    components:{
        "app-button":AppButton,
        "app-modal":AppModal,
    },
    data(){
        return {
            showModal:false,
            showModal2:false,
        }
    },
};
const app = Vue.createApp(App);
app.mount("#app");