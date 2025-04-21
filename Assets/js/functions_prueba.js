const { createApp, ref } = Vue
const app = createApp({
    data() {
        const strMensaje= ref("");
        return {
            strMensaje,
        }
    },
    methods:{
        prueba(){
            this.strMensaje = 1234567;
        }
    }
})
app.mount('#app');