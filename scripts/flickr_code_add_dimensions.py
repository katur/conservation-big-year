import MySQLdb
import csv
import sys
import Image
import urllib
from StringIO import StringIO

def main():
	global db	
	
	# for each row, i.e. each compartment
	c = db.cursor()
	num_pictures = c.execute("""SELECT flickr_code FROM species_list WHERE flickr_code IS NOT NULL ORDER BY id;""")
	for i in range(num_pictures):
		x = c.fetchone()[0]
		pieces = x.split('src="')
		src = pieces[1].split('"')[0]
		f = urllib.urlopen(src)
		s = StringIO(f.read())
		dim = Image.open(s).size
		d = db.cursor()
		d.execute("""UPDATE species_list SET flickr_src=%s, flickr_width=%s, flickr_height=%s WHERE flickr_code=%s;COMMIT;""", (src, dim[0], dim[1], x))
		d.close()
	c.close()


############################
# RUN PROGRAM
############################
#global variables
db = MySQLdb.connect(db="big_year_species", read_default_file="~/.my.cnf")
	
# run main method
main()

# close database connection
db.close()
