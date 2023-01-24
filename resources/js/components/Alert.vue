<template>
    <div :class="estilo" role="alert">
        Marca cadastrada com sucesso!
    </div>
</template>

<script>
export default {
    computed: {
        estilo(){
            return 'alert alert-'+this.tipos
        }
    },
    props: ['tipos']
}
</script>
