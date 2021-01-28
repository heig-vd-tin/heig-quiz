<template>
  <div>
    <b-col sm="2">
        <label> Keywords : </label>
    </b-col>

    <div class="ml-5">
        <b-row class="my-3">
        <b-col sm="2">
            <label> Choose : </label>
        </b-col>
        <b-col sm="6">
            <b-form-input v-model="keyword" list="keyword-list" @change="onKeywordChange"></b-form-input>
            <datalist id="keyword-list">
            <option v-for="k in keywords_list" :key="k.id">{{ k.name }}</option>
            </datalist>
        </b-col>
        <b-col sm="2">
            <b-button variant="success" @click="add">Add</b-button>
        </b-col>
        </b-row>

        <b-table striped hover :items="keywords" :fields="fields">
        <template #cell(Actions)="row">
            <b-button size="sm" @click="onDelete(row.item, row.index, $event.target)" class="mr-1">
            Remove keyword
            </b-button>
        </template>
        </b-table>
    </div>
  </div>
</template>
<script>

export default {
  components: { 
  },

  data() {
    return {
        keyword: null,
        keywords_list: [],
        fields: ['name', 'Actions']
    }
  },

  props:{
    keywords: Array
  },

  computed: {
  },

  methods: {
    getKeyword: function(name) {
        return this.keywords_list.find(o => { return o.name === name })
    },

    loadKeyword : function() {
      axios
      .get('api/keywords')
      .then(resp => {
          this.keywords_list = resp.data.keywords
      })
    },

    add: function(){
        this.keywords.push( this.getKeyword(this.keyword))
        this.keyword=null
    },

    onKeywordChange: function() {
    },

    onDelete: function(keyword, index){
        this.keywords.splice(index, 1)
    }
  },

  mounted: function() {
      this.loadKeyword()
  }
}
</script>