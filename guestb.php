<!-- 
        phiGuest ver. 0.2-1
        changelog:
                * complete translation in english
		* prevents basilar attempts of spamming
        NB. Works only with Php 5+ because uses fprintf
        
        Copyright (C) 2008  Author: Francesco Brundu Mail: name.lastname !at? gmail dot com
        
        This program is free software: you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation, either version 3 of the License, or
        (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->
<?php
	$table = "
	<table>
		<tr>
			<td style=\"width:60%\">";
	echo $table;
	
	// tries to open db.txt in r+ (reading and writing, pointer points to first part of file)
	// REMEMBER: "chmod 777 db.txt" or you won't be able to open it 
	if(($f = fopen("db.txt","r+")) == null) {
        	// if it can't be opened in r+ tries to open it in w (writing)
        	// REMEMBER: "chmod 777 db.txt" or you won't be able to open it
                if(($f = fopen("db.txt","w")) == null) {
                	// else prints an error message
                        echo "<p>Unable to load the database.</p>";
                }
        }else {
                echo "<ul>";
        	// reads all database file and prints it to output
                while (($s = fgets($f)) != null) {
                	if(strcmp($s,"\n")!=0) {
                        	echo "<li>$s: ";
                                $s = fgets($f);
                                echo "<i>$s</i></li>";
                        }
                }
                echo "</ul>";
        }
	
	$table = "
			</td>
			<td>";
	echo $table;
        
	// declares variables name and message
        $name = @$_POST["name"];
        $message = @$_POST["message"];
        // if the name is empty asks for it and message through a form
        if (!isset($name)) {
        	$text = "
                <form name=\"guestbook\" method=\"post\">
                	<br/>
			<table>
				<tr>
                              		<td>Name:</td>
					<td>
						<input type=\"text\" size=\"26\" maxlenght=\"36\" name=\"name\">
					</td>
                               	</tr>
				<tr>
                              		<td>Message:</td>
					<td>
						<input type=\"text\" size=\"26\" maxlenght=\"36\" name=\"message\">
					</td>
                              	</tr>
                               	<tr>
					<td></td>
					<td>
						<input type=\"submit\" value=\"submit\" name=\"submit\">
                                	</td>
				</tr>
			</table>
			<br/>
                </form>
                <br/>";
                echo $text;
	}else{
               	// if the name is not empty and name and message are not spam prints name and message in the database
                $nspam = 2;
		$spam = Array(0 => "http://",1 => "[url=");
		$spamflag = false;
		for($i = 0; $i < $nspam; $i++){
			$pos = strpos($message,$spam[$i]);
			$pos2 = strpos($name,$spam[$i]);
			if(!($pos === false)||!($pos2 === false)){
				echo "<p>SPAM! - Cannot write your message</p>";
				$spamflag = true;
				break;
			}
		}
		if($spamflag == false){
			fprintf($f,$name."\n".$message."\n");
                        echo "<p>Message submited correctly.</p>";
                }
                // asks user to go back to refresh guestbook
		echo "<p><a href=\"./index.php\">&lt;Back to guestbook</a></p>";
        }
        $table = "
			</td>
		</tr>
	</table>";
	// cleans variables and closes the database file
        unset($name);
        unset($message);
        fclose($f);
?>

