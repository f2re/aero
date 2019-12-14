<template>
    <div class="aero-component row">
        

        <div class="stantions col-md-4">
            <div class="input filter w-100">
                <input type="text" class="w-100 input-lg shadow form-control mb-3" name="filter-name" v-model="filtertext">
            </div>
            <div class="stantions-list shadow rounded">
                <ul class="list-unstyled p-2 "
                    >
                    <li class="preloader btn btn-light btn-lg border-secondary w-100">  </li>
                    <li class="btn btn-lg btn-light border-light  mb-1 w-100"
                        v-for="st in stantionList"
                        v-bind:disabled="ajaxBlock"
                        @click="selectStantion(st)"
                        v-bind:class="{ active : st.id==selectedStantion.id, disabled: ajaxBlock, loader:stloading }">
                        {{ st.name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <a v-bind:href="pathToImg"
               v-bind:class="{ 'd-none':pathToImg=='' }">
                <img class="img-fluid img-thumbnail"
                 v-bind:src="pathToImg"
                 v-bind:alt="selectedStantion.name"
                 v-bind:class="{ 'd-none':pathToImg=='' }">
            </a>

            <div class="img-loader" 
                 v-bind:class="{ 'd-none':pathToImg!=''&&!ajaxBlock }">                
                 <img src="/loaders/rings.svg" width="100px"> 
            </div>
        </div>

        <div class="col-md-12 mt-3 mb-3">
            <h3 class="text-dark mt-5">Вставьте в поле ниже код в формате КН-04 и нажмите кнопку СФОРМИРОВАТЬ</h3>
            <textarea class="form-control input" name="raw" 
                    v-model="raw"
                    v-bind:disabled="ajaxBlock" ></textarea>
            <button class="mt-3 btn btn-lg btn-primary border-light  mb-1 "
                    v-on:click="fromraw"
                    v-bind:disabled="ajaxBlock">Сформировать</button>
        </div>


    </div>    
</template>

<script>
    export default {
        data:()=>({
            /**
             * Доступные для выбора станции (по которым есть данные)
             */
            stantions:[],
            /**
             * выбранная станция, по которой шлем запрос
             * @type {Array}
             */
            selectedStantion:{},
            /**
             * путь к картинке
             * @type {String}
             */
            pathToImg:'',
            /**
             * составные части URl для запроса данных
             * @type {Object}
             */
            urls:{
                stantions:'/aero/',
                draw:'/aero/'
            },
            // errors
            errors:[],
            // фильтруем станции по совпадению
            filtertext:'',
            //индикатор загрузки станций
            stloading:false,
            // блокируем все аякс запросы
            ajaxBlock:false,
            // raw text code
            raw:'',
        }),

        mounted() {
            console.log('Component mounted.');
            this.loadStantions();
        },

        computed:{
            stantionList(){
                var filtered = this.stantions;
                var _vue = this;
                // если введена часть строки поиска, то фильтруем
                if (_vue.filtertext!='') {
                    filtered = [];
                    for( var item in this.stantions ){
                        if ( String(this.stantions[item].name).indexOf(_vue.filtertext)!=-1 || String(this.stantions[item].id).indexOf(_vue.filtertext)!=-1 ){
                            filtered.push( this.stantions[item] );                            
                        }
                    }
                }
                return filtered;
            }
        },

        methods:{

            /**
             * select needed stantions
             * @param  {[type]} st [description]
             * @return {[type]}    [description]
             */
            selectStantion(st){
                this.selectedStantion = st;
                this.pathToImg = '';
                // блокируем все вызовы и ставим прелоадер
                this.ajaxBlock=true;
                axios.get(this.urls.draw+this.selectedStantion.id)
                    .then( response =>{
                        this.pathToImg = response.data.path;
                        // разблокируем все
                        this.ajaxBlock=false;
                    } )
                    .catch(e=>{
                        this.errors.push(e);
                        // разблокируем все
                        this.ajaxBlock=false;
                    });
            },

            /**
             * post request from raw text
             * @return {[type]} [description]
             */
            fromraw(){
                
                if ( this.raw=='' ){
                    console.log('надо вставить код!');
                    return false;
                }

                this.selectedStantion = '';
                this.pathToImg = '';
                // блокируем все вызовы и ставим прелоадер
                this.ajaxBlock=true;

                axios.post(this.urls.draw, { raw:this.raw } )
                    .then( response =>{
                        this.pathToImg = response.data.path;
                        // разблокируем все
                        this.ajaxBlock=false;
                    } )
                    .catch(e=>{
                        this.errors.push(e);
                        // разблокируем все
                        this.ajaxBlock=false;
                    });
            },

            /**
             * загружаем список доступных станций
             * @return {[type]} [description]
             */
            loadStantions(){
                // блокируем все вызовы и ставим прелоадер
                this.ajaxBlock=true;
                this.stloading=true;
                // делаем запрос станций
                axios.get(this.urls.stantions)
                    .then( response =>{
                        this.stantions = response.data.stantions;
                        // разблокируем все
                        this.ajaxBlock=false;
                        this.stloading=false;
                    } )
                    .catch(e=>{
                        this.errors.push(e);
                        // разблокируем все
                        this.ajaxBlock=false;
                        this.stloading=false;
                    });
            }
        }
    }
</script>
