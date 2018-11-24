# Choose Your Safari Adventure

A Choose Your Safari Adventure generator using Twine and Wikipedia data

Made for [NaNoGenMo 2018](https://github.com/NaNoGenMo/2018), more informations about the project [here](https://github.com/NaNoGenMo/2018/issues/72).

## Wikipedia Crawler

The `crawler/crawler.php` script crawls Wikipedia pages of animal species and builds a JSON file of the data.
I made some manual corrections to the `data.json` file, I removed empty data and added some areas information by hand.

## Twine Story Generator

The story graph works by multiplying the nodes by 2 at every level until reaching 8 nodes at the same level, then join the choices to 4 nodes and alternate for as long as necessary between 8 and 4.

Here is a schema of the story graph for a 10 level generated story in Twine:
![](https://raw.githubusercontent.com/WhiteFangs/choose-your-safari-adventure/master/graph.PNG)