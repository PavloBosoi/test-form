<template>
    <div>
        <b-card title="Please, fill out the form" class="text-center">
            <b-card bg-variant="danger" text-variant="white" no-body class="py-1 mb-1 " v-if="showAtLeast">
                Please enter at least one entry
            </b-card>
            <b-card bg-variant="danger" text-variant="white" no-body class="py-1 mb-1 " v-if="showMandatory">
                Please fill in all mandatory fields
            </b-card>
            <b-card bg-variant="danger" text-variant="white" no-body class="py-1 mb-1 " v-if="showError">
                Error at server side. Please try again
            </b-card>
            <b-card bg-variant="success" text-variant="white" no-body class="py-1 mb-1 " v-if="showSuccess">
                Data successfully saved
            </b-card>
            <b-card class="mb-3" v-for="(entry,key) in entries" :key="key">
                <button type="button" class="close" v-if="entries.length>1" @click="removeRow(key)">X</button>
                <b-row>
                    <b-col sm="1"><label :for="'input-name-'+key">Name</label></b-col>
                    <b-col sm="10" class="mb-3">
                        <b-form-input :id="'input-name-'+key" size="sm" type="text" v-model="entry.name" :state="entry.name_state"></b-form-input>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col sm="1"><label :for="'input-phone-'+key">Phone</label></b-col>
                    <b-col sm="10">
                        <b-form-input :id="'input-phone-'+key" size="sm" type="text" v-model="entry.phone" :state="entry.phone_state"></b-form-input>
                    </b-col>
                </b-row>
            </b-card>

            <div class="text-right">
                <b-button variant="primary" class="mr-2" @click="addRow()">Add row</b-button><b-button variant="success" @click="submit()">Submit</b-button>
            </div>
        </b-card>
    </div>
</template>

<script>
  export default {
    data: () => {
      return {
        entries: [], // entries array
        showAtLeast: false, //next four variables control warning messages visibility
        showMandatory: false,
        showSuccess: false,
        showError: false
      }
    },
    mounted() {
      this.initEntries(); //init on startup
    },
    methods: {
      initEntries()
      {
        this.entries=[{name:'',phone:''}]; //just create an empty array
      },
      addRow()
      {
        this.entries.push({name:'',phone:''}); //add new empty entry
      },
      removeRow(key)
      {
        this.entries.splice(key,1); //remove entry via index
      },
      submit()
      {
        this.showAtLeast=false; //hide warnings
        this.showMandatory=false;
        this.showSuccess=false;
        this.showError=false;
        this.entries=this.entries.filter(function(el){ //remove entries with both fields empty
          return (el.name!='' || el.phone!='');
        });
        if (this.entries.length==0)
        {
          this.initEntries(); //if all entries was eliminated (because empty) - add one entry
          this.showAtLeast=true; // and show warning
          return;
        }
        var phone_filter = /^\d{6,7}$/; //phone template - 6 or 7 digits
        var form_state=true;
        this.entries.forEach(function(el){
          el.name_state=el.name!=''?null:false; //test name field
          el.phone_state=phone_filter.test(el.phone)?null:false; //test phone field
          form_state=form_state && (el.name_state==null) && (el.phone_state==null); //overall form state
        });
        if (!form_state)
        {
          this.showMandatory=true; //if form have errors - show warning
          return; //and exit
        }
        axios.post("/api/entries",{data:this.entries}) //post data
            .then((response)  =>  {
                this.showSuccess=true; //if success - show message
                this.initEntries(); // and init form
            }, (error)  =>  {
                this.showError=true; //show warning if server return error
            })
      }
    }
  }
</script>
