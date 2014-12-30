# Bubbly

Bubbly is a Test Code Ratio Visualization Tool for Git commits implemented in 
PHP and D3JS. 

Bubbly is based on a blog post by Jonathan Andrew Walter and Miško Hevery in 
which they suggest to

> Visualize your team’s code to get people enthused and more responsible for 
> unit testing. See how commits contribute to or degrade testability. See 
> massive, untested checkins from some developers, and encourage them to change 
> their behavior for frequent, well tested commits.

*Source: [Improving developers enthusiasm for unit tests, using bubble charts][jaw]*

## Setup

Bubbly requires almost no setup. Just checkout the project to the computer that 
has a copy of the Git repository you want to visualize. Then edit the file 
`config.php` to point to that Git repository. If needed, adjust the Regex 
pattern that decides which files are test files. 

Once you have configured Bubbly, open the shell, `cd` into htdocs and run 
`php -S 127.0.0.1:8080`. This will start PHP's internal webserver. Then open your 
browser and navigate to `http://127.0.0.1:8080` to show the visualization. Of 
course, you can also set it up with any other webserver capable of running PHP.
 
By default Bubbly will only visualize the first 500 commits in your repository. 
But you can request any amount by supplying start and end in the query string, e.g. 

    http://127.0.0.1:8080?start=50&end=300
   
The above would visualize the 250 commits from HEAD~50 to HEAD~300.

You should see a page showing something like the image below:

![Bubbly Screenshot 1](http://i.imgur.com/MsmsMnT.png)

Hovering over the bubbles will give you more detailed information about the 
particular commit. You can also deselect committers by clicking on the legend. 

The horizontal line is the average commit ratio over all shown commits. The 
filled line bar shows average commit ratio per day.

There is also a Bar chart listing all committers by their average test code ratio.

![Bubbly Screenshot 2](http://i.imgur.com/v8ma9ja.png)

## System requirements

PHP 5.5 or higher due to usage of Generators and short array syntax.

   [jaw]: http://jawspeak.com/2011/07/16/improving-developers-enthusiasm-for-unit-tests-using-bubble-charts/
