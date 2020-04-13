@extends('layouts.app')
@section('styles')
	<style type="text/css">
	  [v-cloak] {
		display: none;
	  }
	</style>
@stop	
@section('content')	
	
     <h3 class="text-center title-color">Infinite Scroll with Filters in Laravel Using Vuejs - Demo</h3>

    <div class="row">
      <div class="col-md-10 col-lg-offset-1">
        <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-shadow--4dp" v-cloak>
          <div class="col-xs-6 col-sm-8 col-md-9">
            <h4 class="text-capitalize">@{{ filter }} Posts</h4>
          </div>
          <div class="col-xs-6 col-sm-4 col-md-3 " >
            <form class="form-inline" style="padding-top: 20px">
              <div class="form-group">
                <label class="collft control-label">Filter By:</label>
                <select class="form-control" v-model="filter" id="choose" v-on:change="getPosts()">
                  <option value="latest" >Latest Posts</option>
                  <option value="popular" >Most Popular</option>
                  <option value="oldest" >Oldest Posts</option>
                </select>
              </div> 
            </form>
          </div>
          <div class="clearfix"></div>
        </div>  
      </div>
    </div>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">

        <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-shadow--4dp" v-cloak v-for="post in posts">
          <div class="post">
            <a target="_blank" :href="'/blog/'+post.id" class="nounderline">
              <h2 class="post-title">@{{ post.status_id }}</h2>
            </a>
            <h5 class="post-date">Published: @{{ post.created_at }}</h5> 
            <p class="text-justify" v-html="postBody(post.date)"></p>
            <a target="_blank" :href="'/blog/'+post.id" class="demo-nav__button" title="Read More"><b>Read More</b>
              <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-upgraded=",MaterialButton,MaterialRipple">
              <i class="material-icons" role="presentation">arrow_forward</i>
              <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 92.5097px; height: 92.5097px; transform: translate(-50%, -50%) translate(7px, 11px);"></span></span></button>
            </a>
          </div>

          <div class="purchaseorderitems blog-space">
                <h5 >
                   <a v-for="(purchaseorderitem,index) in post.purchaseorderitems" v-if="index < 4" :href=" '/blog/purchaseorderitems/'+purchaseorderitem.id" class="nounderline" style="margin-right: 10px;" target="_blank">
                      <span class="label label-default m-r-10"> @{{ purchaseorderitem.name }} </span>
                   </a>
                </h5>
          </div>

        </div>
        
        <div class="text-center" v-cloak v-if="!completed">
          <img v-if="progress" src="{{ url('images/ajax-loader.gif') }}">
        </div>
        <div class="text-center" v-cloak v-if="completed">
          <h5>No More Posts Found!</h5>
        </div>

      </div>
    </div>
    <hr>
    <h5>For the complete tutorial of how to make this demo app visit the following <a href="https://shareurcodes.com/blog/create-infinite-scroll-with-filters-in-laravel-using-vuejs">Link</a>.</h5>
  
@stop
@push('scripts')	
	<script type="text/javascript">
		 var app = new Vue({
		  el: '#app',
		  data: {
			posts : {!! $posts !!},
			completed : false,
			progress : false,
			filter : "{{ ( isset($_GET['filter'] )) ? $_GET['filter'] : 'latest'  }}",
		  },
		  methods: {
			postBody: function(body) {
			  var newBody = this.strip_purchaseorderitems(body)
			  return newBody.substring(0, 350)+"....";
			},
			strip_purchaseorderitems: function(str, allow) {
			  // making sure the allow arg is a string containing only purchaseorderitems in lowercase (<a><b><c>)
			  allow = (((allow || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

			  var purchaseorderitems = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
			  var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
			  return str.replace(commentsAndPhpTags, '').replace(purchaseorderitems, function ($0, $1) {
				return allow.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
			  });
			},
			getPosts: function(){
			  
			  if (history.pushState) {
				var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?filter=' + this.filter;
				window.history.pushState({path:newurl},'',newurl);
			  }

			  $.LoadingOverlay("show");
			  this.posts = "";
			  self = this;
			  
			  axios.post(newurl)
				   .then(function (response) {
					  $.LoadingOverlay("hide"); 
					  self.posts = response.data;
					  self.completed = false;
					})
				   .catch(function (error) {
						console.log(error);
					});

			},
			infiniteScroll: function(){
			  var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?filter=' + this.filter ;

			  self = this;

			  if (!this.completed &&  !this.progress) {
				this.progress = true
				axios.post(newurl,{
					  offset: self.posts.length ,
					 })
					 .then( function(response) {
						if (response.data.length) {
						  self.posts = self.posts.concat(response.data);
						  self.progress = false;  
						} else {
						  self.progress = false;  
						  self.completed = true;
						}
					  })
					 .catch(function (error) {
						console.log(error);
					  });;
			  }

			},
		  },
		  mounted:function(){
			if (history.pushState) {
				var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?filter=' + this.filter ;
				window.history.pushState({path:newurl},'',newurl);   
			}

			window.addEventListener('scroll', this.infiniteScroll);
		 },
	  });
	</script>
@endpush