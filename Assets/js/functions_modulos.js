import button from "./components/buttons.js"; 


const App = {
    components:{
        "app-button":button
    },
    mounted(){
        
    },methods:{
        
    }
};
const app = Vue.createApp(App);
app.mount("#app");