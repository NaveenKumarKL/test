<template>
<v-container>
  <v-data-table :headers="headers" :items="users" sort-by="calories" class="elevation-1">
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>My CRUD</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <!-- <v-col cols="12" sm="6" md="4">
                    <v-text-field v-model="editedItem.id" label=" name" ></v-text-field>
                  </v-col> -->
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field v-model="editedItem.name" label="name"></v-text-field>
                  </v-col> 

                  <v-col>
                      <div v-if="editedItem.preview">
                          <img :src="editedItem.preview" alt="" height="100px">
                      </div>
                      <div v-else>
                         <img :src="`http://localhost:8000/`+ editedItem.image" alt="" height="100px">                     
                        
                       </div>   
                                
                     <input type="file"  @change="onFileChanged">
                  </v-col>
               
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save()">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
      <template v-slot:item.image="{ item }">
          <img :src="`http://localhost:8000/`+ item.image" style="width: 50px; height: 50px" />
      </template>
    <template v-slot:item.actions="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
      <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template> 

  </v-data-table>
  <template>
 

</template>
  </v-container>
  
</template>

<script>
export default {
  data: () => ({
    dialog: false,  
    headers: [
      {
        text: "id",
        align: "start",
        sortable: false,
        value: "id"
      },
      { text: "name", value: "name" },   
      { text: "image", value: "image" },    
      { text: "Actions", value: "actions", sortable: false }
    ],
    users: [],
    editedIndex: -1,
    editedItem: {
      name: "",    
        image: "",
        preview:""
      
   
    },
    defaultItem: {
    name: "",
   
        image: "",
        preview:""
    }
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
        this.$axios.$get("/sshow").then(res => {
        this.users = res;
        console.log(this.users);
      });
    },
    fetch() {
      console.log("sha");
      this.$axios.$get("/sshow").then(res => {
        this.users = res.data.data;
        console.log(this.users);
      });
    },

    editItem(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.users.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.users.splice(index, 1);
        console.log('delete it')
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    onFileChanged (e) {
            var fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0]);
               this.editedItem.image = e.target.files[0];
           
            fileReader.onload = (e) => {
                this.editedItem.preview = e.target.result
            }
         
           
  },

    save(id) {
      if (this.editedIndex > -1) {
          console.log('edit it')
                    const config = {

                    headers: { 'content-type': 'multipart/form-data' }

                }
                let formData = new FormData();

                formData.append('image', this.editedItem.image);
                 formData.append('name', this.editedItem.name);
               
         this.$axios.$post("rupdate/${id}",formData,config).then(res => { });
        Object.assign(this.users[this.editedIndex], this.editedItem);
      } else {
          console.log(this.editedItem)
                  const config = {

                    headers: { 'content-type': 'multipart/form-data' }

                }
                let formData = new FormData();

                formData.append('image', this.editedItem.image);
                 formData.append('name', this.editedItem.name);
               


       this.$axios.$post("/rregister",formData,config).then(res => { 
      
        console.log(res);
      });
        this.users.push(this.editedItem);
      }
      this.close();
    }
  }
};
</script>

