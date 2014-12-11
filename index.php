<?php
	//include('contact_model.php');
	require_once('contact_model.php');
  $con_db = new contact_model;

// ADD UPDATE delete_contact get_contact

  if (isset($_POST['action']) && $_POST['action']=='ADD' )  {
    $con_db->update_contact(0);
  }
  if (isset($_POST['action']) && $_POST['action']=='UPDATE' )  {
    $con_db->update_contact(1);
  }
  if (isset($_POST['action']) && $_POST['action']=='delete_contact' )  {
     $con_db->delete_contact($_POST['id']);
     //exit();
  }
  if (isset($_POST['action']) && $_POST['action']=='get_contact' )  {
    $contact_array=$con_db->get_contact($_POST['id']);
    echo json_encode($contact_array);
    exit();
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>My Contacts</title>
<!--	<link href="css/contact.css" rel="stylesheet" type="text/css"> -->
	<script src="js/jquery-1.9.1.js" type="text/javascript" charset="utf-8" ></script>
</head>
<body>
	<BR><BR>
	<h2>My Contacts</h2>
	<table border=0 cellpadding=0 cellspacing=0 style="width: 90%;table-layout: fixed;">
		<tr class="tabhead">
			<td class=tabhead width="20%"><b>Name</b></td>
			<td class=tabhead width="15%"><b>Phone Number</b></td>
			<td class=tabhead width="20%"><b>Address</b></td>
			<td class=tabhead width="15%"><b>Email</b></td>
			<td class=tabhead width="15%"><b>Civil Status</b></td>
			<td class=tabhead width="15%">Actions</td>
		</tr>

		<?php
      $contact=$con_db->get_contacts();
       if (mysql_num_rows($contact)>0) {
          while ($row = mysql_fetch_array($contact)) {
   	?>
				<tr valign=center>
					<td class="tabval"><b><?php print $row['name'];?></b></td>
					<td class="tabval"><?php  print $row['phone']; ?></td>
					<td class="tabval"><?php  print $row['address']; ?></td>
					<td class="tabval"><?php   print $row['email']; ?></td>
					<td class="tabval tac" >
		<?php
            switch ($row['status']) {
            case 'S':
                print 'Single';
                break;
            case 'M':
                print 'Married';
                break;
              case 'W':
                print 'Widow';
                break;
              case 'D':
                print 'Divorced';
                break;
            default:
                print 'Single';
                break;
           }
		?>    </td>
					<td class="tabval tac">
						<a class="action_links" onclick="showEditContact( <?php print $row['id'];?> );" href="#"><span class=blue>[Edit]</span></a> |
						<a class="action_links" onclick="deleteContact( <?php print $row['id']; ?>, '<?php print $row['name'];?>' );" href="#"><span class=red>[Delete]</span></a>
					</td>
				</tr>
		<?php
          }
        } else {
		?>
			<tr valign=center>
				<td class="tabval tac" colspan=6><b>Contacts Empty.</td>
			</tr>
		<?php



		echo '<tr valign=bottom>';
		echo '<td bgcolor=#fb7922 colspan=6></td>';
		echo '</tr>';
     }
		?>

	</table>
	<BR><BR>
	<h2 id="action_title">Add Contact</h2>
	<form action="" method="post" id="contact_form">
		<table border=0 cellpadding=0 cellspacing=0 width="55%">
			<tr>
				<td style="width:18%">Name:</td>
				<td style="width:37%"><input type="text" name="name" id="name" size=35 maxlength=200 /></td>
				<td style="width:47%"><span id="name_warning" class="warnings"></span></td>
			</tr>
			<tr>
				<td>Phone Number:</td>
				<td><input type="text" name="phone" id="phone" size=35 maxlength=15 /></td>
				<td><span id="phone_warning" class="warnings"></span></td>
			</tr>
			<tr>
				<td>Address:</td>
				<td><input type="text" name="address" id="address" size=35 maxlength=200 /></td>
				<td><span id="address_warning" class="warnings"></span></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><input type="text" name="email" id="email" size=35 maxlength=200 /></td>
				<td><span id="email_warning" class="warnings"></span></td>
			</tr>
			<tr>
				<td>Civil Status:</td>
				<td colspan="2" style="height:25px;">
					<input type="radio" name="status" id="statusS" value="S" checked /> Single <!--cheked is an attribute of input radio/checkbox type--->
					<input type="radio" name="status" id="statusM" value="M" /> Married
					<input type="radio" name="status" id="statusW" value="W" /> Widowed
					<input type="radio" name="status" id="statusD" value="D" /> Divorced/Separated
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" style="height:35px;">
					<input type="hidden" name="id" id="id" value="">
					<input type="submit" name="action" id="action_submit" value="ADD" onclick="return submit_contact_form();">
				</td>
			</tr>
		</table>
	</form>
	<a id="add_contact_link" href="# " onclick="return false"><h2><u>+ Add Contact</u></h2></a> <!--- javascript:void(0) replace  -->
	<script type='text/javascript' src="js/contact.js"></script>

</body>
</html>