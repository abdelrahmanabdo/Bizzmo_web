<template>
	<div>
	<product
      v-for="product in products"
      v-bind="product"
      :key="product.id"
    ></product>
	</div>
</template>

<script>
	function Product({ id, description, name, productcategory, attachments}) {
		this.id = id;
		this.description = description;
		this.name = name;
		this.category = productcategory.category;
		if (attachments.length > 0) {
			this.image = '/' + attachments[0].path;
		} else {
			this.image = '';
		}
	}
	
	//import ProductComponent from './Product.vue';
	 
    export default {
		props: {
		  userid: String,
		  activeproduct: String
		},
        data() {
            return {
                products: []
            }
        },
        methods: {
            
        },
        created() {
			//axios.get('/api/products').then(({data}) => {
			//	this.products = data;
			//});
			
			axios.get('/api/products').then(({ data }) => {
			  data.forEach(product => {
				this.products.push(new Product(product));
				console.log(product);
			  });
			});
			
			
		}
    }
</script>