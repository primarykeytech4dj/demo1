<table width="50%">
	<caption><h2 align="center">Online Enquiry Received</h2><hr></caption>
	
	<tbody>
		<tr>
			<th>Caller Name :</th>
			<td><?php echo $enquiries['first_name']." ".$enquiries['surname']; ?></td>
		</tr>
		<tr>
			<th>Email ID :</th>
			<td><?php echo $enquiries['primary_email']; ?></td>
		</tr>
		<tr>
			<th>Contact Number :</th>
			<td><?php echo $enquiries['contact_1']; ?></td>
		</tr>
		<tr>
			<th>Lead ID/Reference Number :</th>
			<td><?php echo '<b>'.$enquiries['enq_code'].'</b>'; ?></td>
		</tr>
		<tr>
			<th>Enquiry Time :</th>
			<td><?php echo date('d F, Y h:i:s a', strtotime($enquiries['created'])); ?></td>
		</tr>
		<tr>
			<th>Enquiry Made through :</th>
			<td><?php echo $enquiries['referred_by']; ?></td>
		</tr>
		<tr>
			<th>Message/Requirement :</th>
			<td><?php echo $enquiries['message']; ?></td>
		</tr>
	</tbody>
</table>