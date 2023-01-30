<template>
    <div>
        <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col" v-for="t,key in titulos" :key="key">{{ t }}</th>
        </tr>
        </thead>
        <tbody v-for="m in marcas" :key="m.id">
        <tr>
            <th scope="row">{{ m.id }}</th>
            <td>{{ m.nome }}</td>
            <td><img :src="'/storage/' + m.imagem" alt="" width="45" height="45"></td>
        </tr>
        </tbody>
    </table>
    </div>
</template>

<script>
export default {
    props:['marcas', 'titulos']
}
</script>
