Security
============

Demo Link: www.zillwc.com/int/security/

After extensive research, this was the best method of security I came up with for the project. let me know if any concerns:

Overview
---------
To make sure outsiders do not have access to the files, we will be encrypting the folder names, as well as the filenames.
This will prevent anyone from knowing the location of any specific user or files. Only the user, and the user ONLY, will be able to access their files.
The foldername as well as the filenames will be encrypted with the user's username, password and a specific <a href="http://en.wikipedia.org/wiki/Salt_(cryptography)">salt</a>


Specifics
----------

If the users' folder and files do not exist, the encrypted keys will be generated and the files/folders will be created. Otherwise, the files will be only be read.

Foldername Encryption: <br />
  Salt: "FolderHash"<br />
  Username + Salt + Password
  
Filename Encryption: <br />
  Salt: "FileHash" <br />
  FolderEncryptionKey + Salt


Demo
----------

For the demo, you basically just put in a dummy username, and a dummy password, and it will generate an encrypted key for you, using the algorithm above. This will be the foldername. Next, you click Generate button and it will generate the filename key, and will create the folder and file for you (file will contain a "Secret File Contents" string).
Finally, to test if it worked, go back to the <a href="http://www.zillwc.com/int/security/">homepage</a> and login with the same username and password. Click the Generate button and it SHOULD show you the file contents.

STEPS:
  1. Go to <a href="http://www.zillwc.com/int/security/">homepage</a>
  2. Type in a dummy username & password
  3. Click Generate button (This will create the encrypted file inside the encrypted folder)
  4. Follow steps 1-3 again, using the same username & password
  5. You should get the the file contents of the file.

  
