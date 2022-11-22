# PTPO
This is the main repository for the Programming Team Project Organizer  
  
This entire repository is a Joomla component that can be zipped and installed onto any Joomla 4.0 website  
(Joomla can be downloaded here: https://downloads.joomla.org/cms/joomla4/4-0-0)  
  
In order to install this Joomla component:    
   
  
1. Navigate to your Joomla site and login to the administrator page  
2. Install the zip and pdf files from rtpc. (TODO: Add specific instructions for how this works. Just put files in media folder?)
3. In the Joomla admin panel, go to Users->Groups, find/create the user group that will be Coaches, and read its ID (column on the right)  
4. Then go into "PTPO/site/src/Controller/DisplayController.php" and change "$coachid = 10;" to match the ID of the Coach group  
(Ex: If Coach group's ID column says "27", then DisplayController.php should have "$coachid = 27;")  
5. Zip this entire repository into one file (ex: "PTPO.zip")  
6. In the Joomla admin panel, go to System->Extensions->Install, and select or drag/drop PTPO.zip  
(Joomla should respond with a green message box that says "Installation of the component was successful.")  
7. In the Joomla admin panel, go to Menus->Main Menu and add Menu links for the Catalog and Sets pages  
(Coaches will be automatically redirected to catalogc and setsc)  