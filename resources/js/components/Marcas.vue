<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Marca">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idhelp" texto-ajuda="Opcional. Informe o ID da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar">Pesquisar</button>
                    </template>

                </card-component>
                <!----------------------------------------------------------------------------------------------->
                <card-component titulo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component 
                        :marcas="marcas"
                        :vizualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVizualizar'}"
                        :atualizar="true"
                        :remover="true"
                        :titulos="['ID', 'Nome', 'Imagem']">
                    </table-component>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" v-if="transacaoStatus === 'adicionado'" titulo="Marca cadastrada com sucesso!"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" v-if="transacaoStatus === 'erro'" titulo="Erro ao tentar cadastrar a marca"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Informe a imagem da marca">
                        <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Imagem da Marca" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>

        
        <modal-component id="modalMarcaVizualizar" titulo="Vizualizar Marca">
            <template v-slot:alertas>

            </template>

            <template v-slot:conteudo>
                <div class="form-group">

                </div>

                <div class="form-group">

                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

    </div>
</template>

<script>
    export default {
        computed: {
            token(){
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })

                token = token.split('=')[1]

                token = 'Bearer '+ token

                return token
            }
        },

        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: [],
                busca: { id: '', nome: '' }
            }
        },

        methods: {
            carregarLista(){
                let config = {
                    headers:{
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                
                let url = this.urlBase + '?' + this.urlFiltro

                axios.get(url, config)
                    .then(response =>{
                        this.marcas = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },

            salvar(){
                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers:{
                        'Content-type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registo: ' + response.data.id
                        }
                    })
                    .catch(erros => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: erros.response.message,
                            dados: erros.response.data.errors
                        }
                    })
            },
            pesquisar(){
                let filtro = ''

                for(let chave in this.busca){
                    
                    if(this.busca[chave]){
                        
                        if(filtro != ''){
                            filtro += ';'
                        }

                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }

                if(filtro != ''){
                    this.urlFiltro = 'filtro='+filtro
                }

                this.carregarLista()
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>
