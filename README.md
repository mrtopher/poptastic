# Poptastic

## A Popular Posts Add-on for Statamic CMS

Leverages the **_render__after** hook, a cache file and some additional content front matter to enable popular posts functionality for Statamic.

### Installation

Download files and copy the **poptastic** folder in to your Statamic sites _addo-ons folder.

### How It Works

The add-on works by referencing a new variable it adds to the front matter of your post the first time it's visited called "poptastic". This number is incremented by one for each unique visit. Uniques are tracked by leveraging a cache file which keeps track of IP address, browser and post visitors are viewing.

Once installed you can display a listing of posts ordered by the new "poptastic" variable now added to the post front matter.

                {{ entries:listing sort_by="poptastic" sort_dir="desc" }}
                    <p><a href="{{ url }}">{{ title }}</a></p>
                {{ /entries:listing }}

