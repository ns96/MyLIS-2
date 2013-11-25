<?php

// set the links now
$publication_link = base_url().'group/publications/main';
$add_link = base_url().'group/publications/add';

echo '<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2"><tr><td>
<b><span style="color: #3366FF;">Add New Publication</span></b></td> <td style="text-align: right;">
[ <a href="'.$publication_link.'">Publication List</a> ] 
</td></tr></tbody></table>';

echo "<form method='POST' action ='$add_link'>";
echo '<input type="hidden" name="add_publication_form" value="posted">';

echo printColoredLine('#3366FF', '2px').'<pre></pre>';

// add the title table
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Title</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<input name="title" type="text" size="80" value="Enter Title Here">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Author(s)</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<input name="authors" type="text" size="80" value="'.$user->name.', ">';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '<br>';

// add the type, status, dates here
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Type</b></small></font><br></td>';
echo '<td valign="top" width="25%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Status</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Start Date</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Last Update</b></small></font><br></td>';
echo '<td valign="top" width="20%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Deadline</b></small></font><br></td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#F0F0F0">';
echo '<select name="type" size="1">';
echo '<option value="paper">Paper</option>';
echo '<option value="review">Review</option>';
echo '<option value="research proposal">Research Proposal</option>';
echo '<option value="dissertation">Dissertation</option>';
echo '<option value="patent">Patent</option>';
echo '</select>';
echo '</td>';
echo '<td valign="top" width="25%" bgcolor="#F0F0F0">';
echo '<select name="status" size="1">';
echo '<option value="proposed">Proposed</option>';
echo '<option value="in progress">In Progress</option>';
//echo '<option value="submitted">submitted</option>';
//echo '<option value="published">published</option>';
echo '</select>';
echo '</td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0">';
echo '<input name="start_date" type="text" size="15" value="'.getLISDate().'">';
echo '</td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0">';
echo 'n/a';
echo '</td>';
echo '<td valign="top" width="20%" bgcolor="#F0F0F0">';
echo '<input name="end_date" type="text" size="15" value="mm/dd/yyyy">';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';

// add the abstract table here
echo '<br>';
echo '<table cellpadding="2" cellspacing="2" border="0" width="100%"><tbody>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Abstract</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<textarea name="abstract" rows="5" cols="80">Enter Abstract Here</textarea>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top" width="15%" bgcolor="#b5cbe7"><font color="#212063"><small><b>Comments</b></small></font><br></td>';
echo '<td valign="top" width="85%" bgcolor="#F0F0F0">';
echo '<textarea name="comments" rows="5" cols="80">Enter Any Comments Here</textarea>';
echo '</td>';
echo '</tr>';
echo '</tbody></table>';
echo '<br>';

echo "<div style=\"text-align: right;\">";
echo '<input type="submit" value="Add Publication" 
style="background: rgb(238, 238, 238); color: #3366FF">';
echo '</div></form>';
