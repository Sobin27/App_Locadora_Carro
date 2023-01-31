<template>
    <div>
        <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col" v-for="t,key in titulos" :key="key">{{ t }}</th>
            <th v-if="vizualizar.visivel || atualizar || remover">

            </th>
        </tr>
        </thead>
        <tbody v-for="m in marcas" :key="m.id">
        <tr>
            <th scope="row">{{ m.id }}</th>
            <td>{{ m.nome }}</td>
            <td><img :src="'/storage/' + m.imagem" alt="" width="45" height="45"></td>
            <td v-if="vizualizar.visivel || atualizar || remover">
                <button v-if="vizualizar.visivel" class="btn btn-outline-primary btn-sm" :data-bs-toggle="vizualizar.dataToggle" :data-bs-target="vizualizar.dataTarget" @click="setStore(marcas)">Vizualizar</button>
                <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                <button v-if="remover" class="btn btn-outline-danger btn-sm">Remover</button>
            </td>
        </tr>
        </tbody>
    </table>
    </div>
</template>
<script>
export default {
    props:['marcas', 'titulos', 'atualizar', 'vizualizar', 'remover'],
    methods: {
        setStore(id){
            console.log(id)
        }
    }
}
</script>
