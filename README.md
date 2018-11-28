# Choose Your Safari Adventure

A Choose Your Safari Adventure generator using Twine and Wikipedia data!

The result is an HTML interactive fiction rewritten everytime you reload the page!

Made for [NaNoGenMo 2018](https://github.com/NaNoGenMo/2018), more informations about how I worked on the project [here](https://github.com/NaNoGenMo/2018/issues/72).

## How it works

The generation script is the `generation/generator.php` file. It loads the wikipedia data from the JSON file, builds a story graph and then generates Twine passage elements using the data and some hardwritten narrative resources. All this is wrapped in an HTML file where the twine scripts are loaded to automatically transform the Twine objects into an HTML interactive story once the web page is fully loaded.

## Wikipedia Crawler

The `crawler/crawler.php` script crawls Wikipedia pages of animal species and builds a JSON file of the data.
I made some manual corrections to the `data.json` file, I removed empty data and added some areas information by hand.

## Story Graph

The story graph works by multiplying the nodes by 2 at every level until reaching 8 nodes at the same level, then join the choices to 4 nodes and alternate for as long as necessary between 8 and 4. The code is in the `generation/graph.php` file.

You can debug the story in Twine by setting the `$twineDebug` variable at true in the generation script.

Here is a schema of the story graph for a 10 level generated story in Twine:
![](https://raw.githubusercontent.com/WhiteFangs/choose-your-safari-adventure/master/graph.PNG)