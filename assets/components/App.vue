<template>
    <component :is="activeComponent"></component>
</template>

<script>
    import Jwt from "./page/Jwt.vue";
    import Cors from "./page/Cors.vue";
    import Login from "./page/Login.vue";

    export default {
        created() {
          const app = document.getElementById('app');
          switch (app.dataset.component) {
            case 'jwt':
              this.activeComponent = Jwt;
              break;
            case 'cors':
              this.activeComponent = Cors;
            case 'login':
              this.activeComponent = Login;
            default:
              new Error('No component')
          }
        },
        data () {
          return {
            activeComponent: null
          }
        }
    }
</script>
