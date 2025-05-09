5/26/2024
Completing registration flushed out some issues with roles so it was required to correct them here aswell.

This Version has a significant update to the way roles are being done. I have created new migration files for this just drop all tables and then run migrate.php twice. It will create all new tables and the second migration will insert all the predefined information aswell as create a Super Admin User (Super Admin User: superadmin@invisibleintercom.com Pass: 1234 ), (Admin User: admin@invisibleintercom.com Pass: 1234 ), (Dealer User: dealer@invisibleintercom.com Pass: 1234 ).

Roles now have layers to allow for custom roles without the ability to give more permision than giver is allowed. The way it works is Super Admin can create Admins (These will be High Level Admins for the company InvisibleIntercom). Admins can Create Dealers and then Dealers can create Organization and property admins. Admins and Dealers will both have access to create/modify/delete any user below their respective Layer. This may still need to be expanded.

There is also now smtp happening there is an example send_invitation.php file. This is for registration handling. Without this file configured you will need to customize this url and add your token ( http://localhost:9999/register.php?token= ).

I have updated the current data points used to reflect the updated schema.

## Creating a New Migration

To create a new migration, follow these steps:

1. **Update the Data Points**: Ensure the current data points reflect the updated schema.

2. **Create the Migration Script**: Use the following command to create a new migration file. Replace `file_nameWithoutTimestamp` with your desired migration base name:

   ```bash
   php include/helper_functions/create_migration.php file_nameWithoutTimestamp
   ```

   This command will generate a new migration file with a timestamped name based on the provided base name.

## Running all migration
To run all migration use this command:
```bash
   php migrate.php
```

## Initial Setup For SMSIO

Logged in as Super Admin go to the Super Admin Settings page. There will be a add hardware section. This section can be used for any config based hardware. You will need to give it a name and paste in the base config you want to use. 
Once you have created the hardware it will now show in add hardware modal in the property builder under hardware tab. This needs to be continued and only works for creating, saving and downloading an smsio config. Note: I have added a file baseconfig.dat in include/hardware/smsio directory for you to use. I did not auto populate database to allow you all to see how the whole process works. This will be modified for other hardware types. There is also a mapping.txt file I started that includes information that will be needed for full integration past config.  

## Storage For Images, Additional Files Etc

I have setup an additional storage drive on the vps server. Here is the Directory /var/www/data/. This is a full individual drive that we will use for Property Images, Profile Pictures, Any large Additional files or whatever else that is not directly apart of the website. This drive is currently 50gb and is able to be expanded. This directory can only be accessed via php. Here is an example of how to write to the drive:

   $file = '/var/www/data/testfile.txt';
   file_put_contents($file, 'Test content');
   echo 'File written successfully!';

## Environment Specific
For me I just added a directory named data into my wwwroot since I am using iis in my development environment. I set permissions for my iis user to be able to read and write to that new folder. I modified my php.ini file Since data is in the same directory (wwwroot) as my Invisible I can change my path to ../data/testfile.txt. I then modified my php.ini file to be the same as the server just with my environment paths. Here is the section:  
``
; open_basedir, if set, limits all file operations to the defined directory
; and below.  This directive makes most sense if used in a per-directory
; or per-virtualhost web server configuration file.
; Note: disables the realpath cache
; https://php.net/open-basedir
open_basedir = "C:\inetpub\wwwroot\Invisible;C:\inetpub\wwwroot\data"
``
Restart IIS. Now I can access the directory just like I would on the server. 
