<html>
<head>

	<!-- skeleton -->
	<link href="<?php echo asset('libs/skeleton/css/normalize.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo asset('libs/skeleton/css/skeleton.css') ?>" rel="stylesheet" type="text/css">

	<title>Restfull Backbone/Laravel App</title>

</head>
<body>

	<div class="container">
		<h1>User Manager</h1>
		<hr/>
		<div class="page"></div>
	</div>

	<script type="text/template" id="user-list-template">

		<a href="#/new">New</a>
		<hr/>
		<table class="table striped">
			<thead>
				<tr>
					<th>First name</th>
					<th>Last name</th>
					<th>Age</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<% _.each(users, function (user){ %>
					<tr>
						<td><%= user.get('firstname') %></td>
						<td><%= user.get('lastname') %></td>
						<td><%= user.get('age') %></td>
						<td><a href="#/edit/<%= user.id %>">Edit</a></td>
					</tr>
				<% }); %>
			</tbody>
		</table>

	</script>

	<script type="text/template" id="edit-user-template">

		<form class="edit-user-form">
			<legend><%= user ? 'Update' : 'Create' %> User</legend>
			
			<label>First name:</label>
			<input name="firstname" type="text" value="<%= user ? user.get('firstname') : '' %>" />

			<label>Last name:</label>
			<input name="lastname" type="text" value="<%= user ? user.get('lastname') : '' %>" />

			<label>Age:</label>
			<input name="age" type="text" value="<%= user ? user.get('age') : '' %>" />

			<hr/>
			<button type="submit"><%= user ? 'Update' : 'Create' %></button>
			<% if(user){ %>
				<input type="hidden" name="id" value="<%= user.id %>" />
				<button type="button" class="delete">Delete</button>
			<% } %>
		</from>

	</script>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.1/backbone-min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-serialize-object/2.0.0/jquery.serialize-object.compiled.js"></script>

	<script type="text/javascript">

		$.ajaxPrefilter( function( options, originalOptions, jqXHR ){
			options.url = "http://saviobackbone" + options.url;
		});

		var Users = Backbone.Collection.extend({
			url: '/users'
		});

		var User = Backbone.Model.extend({
			urlRoot: '/users'
		});

		var UserList = Backbone.View.extend({
			el: '.page',
			render: function(){
				var that = this;
				var users = new Users();
				users.fetch({
					success: function( users ){
						var template = _.template($('#user-list-template').html());
						var html = template({users: users.models});
						that.$el.html(html);
					}
				});
			}
		});

		var EditUser = Backbone.View.extend({
			el: '.page',
			render: function(options){
				var that = this;
				if(options.id){
					that.user = new User({id: options.id});
					that.user.fetch({
						success: function(user){
							var template = _.template($('#edit-user-template').html());
							var html = template({user: that.user});
							that.$el.html(html);
						},
						error: function(){
							console.log('error');
						}
					});
				}else{
					var template = _.template($('#edit-user-template').html());
					var html = template({user: null});
					this.$el.html(html);
				}
			},
			events: {
				'submit .edit-user-form': 'saveUser',
				'click .delete': 'deleteUser'
			},
			saveUser: function (ev){
				var userDetails = $(ev.currentTarget).serializeObject();
				var user = new User();
				user.save(userDetails, {
					success: function(user){
						console.log('success');
						console.log(user.toJSON());
						router.navigate('', {trigger: true});
					},
					error: function(user){
						console.log('error');
						console.log(user.toJSON());
					}
				});
				return false;
			},
			deleteUser: function(ev){
				this.user.destroy({
					success: function (){
						router.navigate('', {trigger:true});
					}
				});
				return false;
			}
		});

		var Router = Backbone.Router.extend({
			routes: {
				'': 'home',
				'edit/:id': 'editUser',
				'new': 'editUser'
			}
		});

		var userList = new UserList();
		var editUser = new EditUser();

		var router = new Router();
		router.on('route:home', function(){
			userList.render();
		});
		router.on('route:editUser', function(id){
			editUser.render({id:id});
		});

		Backbone.history.start();

	</script>

</body>
</html>