<?php headerAdmin($data); ?>
<app-button btn="primary" icon="new" @click="showModal = true"></app-button>
<app-button btn="success" icon="edit" @click="showModal2 = true"></app-button>
<app-modal title="Modal" id="modalElement" v-model="showModal"></app-modal>
<app-modal title="Modal2" id="modalElement2" v-model="showModal2"></app-modal>
<?php footerAdmin($data); ?>