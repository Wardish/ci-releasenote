# ci-releasenote

Reads a release note written by markdown, and return objects. as follow.

```php
    $release_notes[$number] = (object)array(
        'title' => $title,
        'date' => $date->format('Y/m/d　H:i'),
        'name' => $name,
        'file' => $file,
        'html' => $html
    );
```
### Installation via Composer

If you like Composer:

~~~
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Wardish/ci-releasenote"
    }
  ],
  "require": {
    "wardish/ci-releasenote": "master"
  }
}
~~~

And run `install.php`:

~~~
$ php vendor/wardish/ci-releasenote/install.php
~~~

* The above command always overwrites exisiting files.
* You must run it at CodeIgniter project root folder.

### Folder Structure

~~~
codeigniter/
├── application/
│   └── release_notes/
│        ├── yyyymmddhhss_<versionName>.md ... Relase note with markdown format.
│        └── README.md                     ... don't touch
└── vendor/
~~~

### format of Markdown file

Markdown files, are needs to fixed phrease. on first lines.  
The `{#title}` placeholder follow by version strings.

```
BETA_0.00 {#title}
========
```

### Usage

You have to create a controller.  
As an example.

```php
class Release_notes extends CI_Controller
{

    public function __construct() {
        parent::__construct();

        $this->load->library('release_note');
    }

    function index()
    {
        $release_notes = $this->release_note->find_release_notes();
        $this->tal->set('notes', $release_notes);
        $this->tal->view('release_note/index');
    }

}
```

#License & Authors

**Author:** Wardish,LLC ([www.wardish.jp](http://www.wardish.jp))

**Copyright:** 2007-2017, Wardish,LLC.

```
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```