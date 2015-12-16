# hltv & csgolounge scraper
A scraper for hltv.org and csgolounge.com, written for my project: www.brintos.dk/csgo

This is a scraper written in java with the purpose of scraping latest matches and results from hltv.org, 
as well as gathering odds from csgolounge.com. These results are then added to a database consisting of CS:GO players, teams, maps,
matches, and competitions

As well as being a scraper, there are commands that allow database interaction through the terminal




# Installation
1. in order to take full effect of this scraper, you'll need a database structure to match even though you can make the program print to terminal or text by not using the database class. My database structure looks like this at the time of typing this:

2. Replace database information in database.java marked with "DATABASE_URL", "USERNAME", "PASSWORD"

3. Replace userAgent in Main.java
# Dependencies
- JDBC driver
- JSoup