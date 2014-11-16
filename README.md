STARK 
========
[![Build Status](https://travis-ci.org/polishdeveloper/stark.svg?branch=master)](https://travis-ci.org/polishdeveloper/stark)
[![Coverage Status](https://coveralls.io/repos/polishdeveloper/stark/badge.png?branch=master)](https://coveralls.io/r/polishdeveloper/stark?branch=master)

Stark is a project to perform VCS hooks and in case of errors stop action and report to user.
Stark's use of simple XML hooks file and extensible PHP task classes make it an easy-to-use and highly flexible VCS hooks framework.

Stark was written in the way how Phing is managing the build system - one simple XML rules all tasks. 
Additionally system of properties is very similar to one used in Phing, if you know how to use Phing,
you're ready to start using Stark.


* Supports multiple actions per hook
* Supports multiple VCS systems
* Define everything in one portable XML file
* Plug-in architecture allows super-easy extensions

Stark solved all issues with creating VCS-related scripts. With one clean XML you can manage all hooks in all your repositories . With big set of predefined tasks you are able to start checking your commits integrity just in a few seconds.

  
  
Usage
---
  
```bash
stark type action arg1, arg2, arg3, ... argN
```

| param | info  |
| ----- | ----- |
| type  | repository type. At this moment STARK supports only GIT and SVN |
| action  | action you want to perform. Stark will take all tasks definied under hooks/hook/{ACTION} tree and execute them. If at least one of them fails script will stop commit and output error message [only on pre-actions] |
| arg1, arg2, arg3 | arguments set for repository |
  

  
Sample XML definition
---
  
  ```xml
  <stark>
            <hooks>
                <pre-commit>
                    <comment minLength="10" notEmpty="true" />
                    <comment regex="/[a-zA-Z0-9 ]+/"/>
                    <fileFilter extensions="log,ini" />
                    <phpLint />
                </pre-commit>
                <post-commit>
                    <mail to="raynor@dev" subject="Post commit" body="Valid commit by ${author}"/>
                    <log file="/tmp/vcs.log" message="Commit was made by ${author} on ${date} ${time}. Commit message : ${message}" />
                </post-commit>
            </hooks>
        </stark>
```

By this XML file during the pre-commit action STARK will check 
 * comment is not empty and contains at least 10 characters
 * comment matches [a-zA-Z0-9]+ regex.
 * user is not commiting .log or .ini files
 * php files have correct syntax
 
And during  the post-commit action STARK will 
 * send email to raynor@dev with subject *Post commit* and information about author
 * will log in /tmp/vcs.log information about action (author, date and time)
   
   
Available Tasks
----------------
   
Comment
---
    <comment minLength="10" notEmpty="true" regex="/[a-zA-Z]+/" />
   
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| minLenght  | 0  | no | Minimum comment lenght|
| notEmpty   | true | no | can comment be empty |
| regex      | /.*/ | no | regular expression comment has to match |
   

| ExternalCommand 
 ---
    <externalCommand command="ls -la" errorMessage="Cannot execute command" />
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| command    |  null    | yes       | command to run |
| errorMessage | Execution of remote command '%s' failed with code '%s | no | Error message to show when command fails (sends exitCode different than successExitCode |
| successExitCode | 0 | no | success exit code |
| includeOuput | false | no | when tasks fails, should error message contain output of script |
 
   
   
File Filter
---
    <fileFilter extensions="ini,log,tmp" regex="^\/tmp\/.*$" />
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| extensions    |  null    | no       | comma sepearated list of extensions to filter |
| regex | '' | no | file paths cannot match given regular expression |
| admins | '' | no | comma separated list of authors who are allowed to commit |
    
Log
---
    <log file="/tmp/vcs.log" meesage="User ${author} made a commit on ${date} ${time}" />
   
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| file  | '' | yes | log file name|
| message | '' | yes | line to put into log file |
   
Mail
---
    <mail to="admin@dev" subject="Successful commit" body="User ${author} made a commit on ${date} ${time}" />
   
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| to  | '' | yes | receiver of message |
| subject | '' | yes | message subject |
| body | '' | yes | email content |
| from | stark@localhost | no | sender |
| replyTo | none@localhost | no | replyTo address |
   
    
PHPLint
---
    <phpLint  />
 
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| fileExtensions  | php,php4,php5,phtml | no | comma separated list of php file extensions |
    


RegisterRepository
---
Registers a new repository for given hooks.
    <registerRepository name="vcs" classname="myVCSClass"  />
   
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| name  | '' |  yes | repository name |
| classname  | '' |  yes | class that implments **\Stark\core\Repository** interface |
To use new repository you have to run Stark passing new repository name 
```bash
stark vcs myAction arg1, arg2, arg3
```


RegisterTask
---
    <registerTask name="myTask" classname="myTaskClass"  />
   
| Parameter  | Default value | Required | Description |
| ---------- | ------------- | --------- | ---------- |
| name  | '' |  yes | repository name |
| classname  | '' |  yes | class that extends **\Stark\core\Tasks\Task** class |
   
   

Author
--------

Piotr Miazga <piotr.miazga@yahoo.com>

License
--------

GNU
