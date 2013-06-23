import MySQLdb
import csv
import sys

def main():
	global db	
	filename="/Users/katherine/Desktop/CBY_official_list.txt"
	data = open(filename, "r").readlines()
	process_file(data)

def process_file(data):
	global db
	
	# for each row, i.e. each compartment
	count = 0
	started = False
	species_name = "Fred"
	for row in data:
		seen = 0
		if "START" in row:
			dictionary = {}
		
		elif "=" in row:
			pieces = row.split("=")
			dictionary[pieces[0]] = pieces[1].split("\n")[0][1:-1]

		elif "END" in row:
			species_name = dictionary["species"]
			
			c = db.cursor()
			num_lines = c.execute("""SELECT id FROM species_list WHERE common_name=%s;COMMIT;""", species_name)
			if num_lines > 1:
				sys.exit("Error: too many species match " + species_name)
			elif num_lines < 1:
				sys.exit("Error: " + species_name + " not found")
			species_id = c.fetchone()
			c.close()
			
			if dictionary.has_key("src"):
				code = '<img'
				if dictionary.has_key("alt"):
					code += ' alt="' + dictionary["alt"] + '"'
				if dictionary.has_key("orientation"):	
					if "HORIZONTAL" in dictionary["orientation"]:
						url_small = dictionary["src"].rstrip(".jpg") + "_m" + ".jpg"
						code += ' src="' + url_small + '"'
					elif "VERTICAL" in dictionary["orientation"]:
						url_small = dictionary["src"].rstrip(".jpg") + "_n" + ".jpg"
						code += ' src="' + url_small + '"'
				else:
					code += ' src="' + dictionary["src"] + '"'
				
				code += ' />'
				
				print species_name + code

				c = db.cursor()
				c.execute("""update species_list set flickr_img_very_small=%s where id=%s;commit;""", (code, species_id[0]))
				c.close()
'''
			if dictionary.has_key("src"):
				code = '<img'
				if dictionary.has_key("alt"):
					code += ' alt="' + dictionary["alt"] + '"'
				if dictionary.has_key("orientation"):	
					if "horizontal" in dictionary["orientation"]:
						url_small = dictionary["src"].rstrip(".jpg") + "_n" + ".jpg"
						code += ' src="' + url_small + '"'
					else:
						code += ' src="' + dictionary["src"] + '"'
				else:
					code += ' src="' + dictionary["src"] + '"'
				
				code += ' />'
				
				print species_name + code

				c = db.cursor()
				c.execute("""update species_list set flickr_img_small=%s where id=%s;commit;""", (code, species_id[0]))
				c.close()
'''

'''
			if dictionary.has_key("src"):
				code = ''
				
				if dictionary.has_key("href"):
					code += '<a ';
					if dictionary.has_key("title"):
						code += 'title="' + dictionary["title"] + '" '
					code += 'href="' + dictionary["href"] + '">'
				
				code += '<img '
				if dictionary.has_key("alt"):
					code += 'alt="' + dictionary["alt"] + '" '
				code += 'src="' + dictionary["src"] + '" '
				if dictionary.has_key("orientation"):	
					if "HORIZONTAL" in dictionary["orientation"]:
						code += 'width="500" '
					elif "VERTICAL" in dictionary["orientation"]:
						code += 'height="500" '
				
				code += '/>'

				if dictionary.has_key("href"):
					code += '</a>'

				c = db.cursor()
				c.execute("""UPDATE species_list SET flickr_code=%s WHERE id=%s;COMMIT;""", (code, species_id[0]))
				c.close()
'''


'''
			if dictionary.has_key("seen"):
				c = db.cursor()
				c.execute("""UPDATE species_list SET seen_this_year="1" WHERE id=%s;COMMIT;""", species_id)
				c.close()
				if dictionary.has_key("state") and dictionary.has_key("date"):
					date = dictionary['date']
					state = dictionary['state']
					c = db.cursor()
					c.execute("""INSERT INTO sightings (species_id, date, state) VALUES (%s, %s, %s)""",(species_id[0],date,state))
					db.commit()
					c.close()
				elif dictionary.has_key("date"):
					sys.exit("Error: date without state")
				elif dictionary.has_key("state"):
					sys.exit("Error: state without date")
				else:
					c = db.cursor()
					c.execute("""INSERT INTO sightings (species_id) VALUES (%s);COMMIT;""", species_id)
					c.close()
'''			
'''
			# following three lines for getting the list down to possible-to-see
			c = db.cursor()
			c.execute("""UPDATE species_list SET possible_to_see="1" WHERE id=%s;COMMIT;""", species_id)
			c.close()
'''


############################
# RUN PROGRAM
############################
#global variables
db = MySQLdb.connect(db="big_year_species", read_default_file="~/.my.cnf")
	
# run main method
main()

# close database connection
db.close()
