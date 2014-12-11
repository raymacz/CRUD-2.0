$(document).ready(function(){
	showAddContact();

	$("#add_contact_link").click(function(){
		showAddContact();
	});
});

function showAddContact() //this function initializes the content
{
	$("#action_title").html("Add Contact"); //get html format string or .text()
	$("#action_submit").val("ADD"); //get value of object
	$("#add_contact_link").hide();
	$("#name").val("");
	$("#phone").val("");
	$("#address").val("");
	$("#email").val("");
	$("#statusS").attr('checked', true);
	$("#id").val("");
}

function showEditContact(id)
{ $.html
	$("#action_title").html("Edit Contact");
	$("#action_submit").val("UPDATE");
	$("#add_contact_link").show();

	$.post('index.php',
	{
		id:id,
		action:'get_contact'
	},
	function(data)
	{
		$("#name").val(data['name']);
		$("#phone").val(data['phone']);
		$("#address").val(data['address']);
		$("#email").val(data['email']);
		$("#status"+data['status']).attr('checked', true);
		$("#id").val(data['id']);
	},
	"json");
}

function submit_contact_form()
{
	var isValid1 = true;
	var isValid2 = true;
	var isValid3 = true;
	$("#name_warning").empty();
	$("#phone_warning").empty();
	$("#email_warning").empty();

	if($("#name").val() == '')
	{
		$("#name_warning").append(" Please input name.");
		isValid1 = false;
	}
	if( !phoneOk( $("#phone").val() ) )
	{
		$("#phone_warning").append(" Please input phone number.");
		isValid2 = false;
	}
	if( !emailOk( $("#email").val() ) )
	{
		$("#email_warning").append(" Please input a valid email.");
		isValid3 = false;
	}

	if(isValid1 == true && isValid2 == true && isValid3 == true)
	{
		$.ajax({
		  type: "POST",
		  url: "index.php",
		  data: $('#contact_form').serialize() //serialize() is a jquery function that prepares string before it is posted to server
		}).done(function( response ) {
			console.log('success');
			return true;
		});
	}
	else
	{
		return false;
	}
}

function phoneOk(ph)
{ 
	var numericExpression = /^[0-9]+$/;
	if(ph != "")
	{
		if(ph.match(numericExpression)) // if string matches to an expression
			return true;
		else
			return false;
	}
	else
	{
		return false;
	}
}

function emailOk(em)
{
	var filter = /^([A-Za-z0-9_\-\.])+\@(?:[A-Za-z0-9-]+\.)+([A-Za-z]{2,4})$/;
	if( em != "" )
	{
		if ( filter.test( em ) ) //javascript function that searches if string exist .test()
			return true;
		else
			return false;
	}
	else
	{
		return true;
	}
}

function deleteContact( id, name )
{
	if( confirm("Are you sure you want to delete "+name+" ?") )
	{
		$.ajax({
		  type: "POST",
		  url: "index.php",
		  data: { id: id, action: 'delete_contact' }
		}).done(function( response ) {
			if( response )
				alert("Contact has been deleted!");
			window.location.href = "";
		});
	}
}