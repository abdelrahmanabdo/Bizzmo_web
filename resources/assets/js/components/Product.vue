<template>
  <div class="row">
    <div class="col-md-2">
      <img :src="image" width="150" height="150">
    </div>
    <div class="col-md-10">
		<h3>{{ name | properCase }}</h3>
		<div class="form-group form-group--view">
			<label for="companyname" class="bm-label col-sm-3 col-xs-12">Description</label>
			<p class='form-control-static col-sm-9'>{{ description }}</p>
		</div>
		<div class="form-group form-group--view">
			<label for="companyname" class="bm-label col-sm-3 col-xs-12">Category</label>
			<p class='form-control-static col-sm-9'>{{ category }}</p>
		</div>
    </div>
  </div>
</template>
<script>
  export default {
    computed: {
      
    },
    methods: {
      
    },
    props: ['id', 'name', 'description', 'category', 'image'],
    filters: {
      properCase(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      }
    }
  }
</script>
