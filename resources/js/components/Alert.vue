<template>
    <div :class="estilo" role="alert">
        {{ titulo }}
        <hr>

        <p>{{ detalhes.message }}</p>
        <br>

        <ul v-if="detalhes.dados">
            <li v-for="e, key in detalhes.dados" :key="key">
                {{ e[0] }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    computed: {
        estilo(){
            return 'alert alert-'+this.tipos
        }
    },
    props: ['tipos', 'titulo', 'detalhes']
}
</script>
