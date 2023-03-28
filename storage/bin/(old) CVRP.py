#!/usr/bin/env python
# coding: utf-8

# In[1]:

import copy
import math
import numpy as np
import pandas as pd
import random as rand
import sys
import mlrose_hiive as mlrose
# from sklearn import datasets
from sklearn.cluster import KMeans
# from sklearn.metrics import accuracy_score
from sklearn.metrics import silhouette_score
# from sklearn.preprocessing import MinMaxScaler
from geopy import distance
from typing import Union
from deap import base, creator, tools

# In[2]:

class KmeansGroup():
	def __init__(self, centroid, allNodes = []):
		self.centroid = centroid
		self.allNodes = allNodes
	def getCentroid(self):
		return self.centroid
	def getAllNodes(self):
		return self.allNodes
	def getDemand(self):
		totalDemand = 0
		for node in self.allNodes:
			totalDemand += node.getDemand()
		return totalDemand
	def setCentroid(self, centroid):
		self.centroid = centroid
	def setAllNodes(self, allNodes):
		self.allNodes = allNodes
	def setDemand(self, cap):
		self.demand = cap
	
	
class DepotInfo():
	def __init__(self, number, address, lat, lng):
		self.number 		= number
		self.address 		= address
		self.lng 			= lng
		self.lat 			= lat
	def getLocation(self):
		return [self.lat, self.lng]
	def setLocation(self, lng, lat):
		self.lng = lng
		self.lat = lat	
		
class NodeInfo():
	def __init__(self, number, uuid, sex, first_name, last_name, phone_number, delivery1, delivery2, lat, lng, demand):
		self.number					 	=	int(number)
		self.uuid						=	uuid
		self.sex						=	sex
		self.first_name				 	=	first_name
		self.last_name					=	last_name
		self.phone_number				=	phone_number
		self.delivery1					=	delivery1
		self.delivery2					=	delivery2
		self.lat						=	lat
		self.lng						=	lng
		self.demand					 	=	int(demand)
	def getNumber(self):
		return self.uuid
	def getDemand(self):
		return self.demand
	def getLocation(self):
		return [self.lat, self.lng]
	def setNumber(self, number):
		self.number = number
	def setDemand(self, demand):
		self.demand = demand
	def setLocation(self, lng, lat):
		self.lng = lng
		self.lat = lat
	def getInfo(self):
		return {
			"number"					:	self.number,
			"uuid"						:	self.uuid,
			"sex"						:	self.sex,
			"first_name"				:	self.first_name,
			"last_name"					:	self.last_name,
			"phone_number"				:	self.phone_number,
			"delivery1"					:	self.delivery1,
			"delivery2"					:	self.delivery2 if self.delivery2 is None else str(),
			"lat"						:	self.lat,
			"lng"						:	self.lng,
			"demand"					:	self.demand,
		}

def calBearing(a: list, b: list):
	lat1, lng1 = a[0], a[1]
	lat2, lng2 = b[0], b[1]
	dlng = lng2 - lng1
	rlat1, rlat2, rdlng = math.radians(lat1), math.radians(lat2), math.radians(dlng)
	x = math.cos(rlat2) * math.sin(rdlng)
	y = math.cos(rlat1) * math.sin(rlat2) - math.sin(rlat1) * math.cos(rlat2) * math.cos(rdlng)
	bearing = np.arctan2(x, y)
	bearing = np.degrees(bearing)
	return bearing

def convertBearing(bearing):
	if bearing < 0:
		bearing += 360
	return bearing

def isInRange(ccBearing, cnBearing):
	ccBearing = convertBearing(ccBearing)
	cnBearing = convertBearing(cnBearing)
	angleDiff = (cnBearing - ccBearing + 180) % 360 - 180
	if angleDiff <= 135 and angleDiff >= -135:
		return True
	return False

def calDistance(n1: NodeInfo, n2:NodeInfo):
	n1Location = n1.getLocation()
	n2Location = n2.getLocation()
	
	result = distance.distance(n1Location, n2Location).km
	
	return result
	
def getMinDistance(c1List, c2List):
	bestDis = +math.inf
	for n1 in c1List:
		for n2 in c2List:
			distance = calDistance(n1, n2)
			if distance < bestDis:
				bestDis = distance
	return bestDis

def getSuitNodes(cNodes: list, cent, ccBearing):
	suitNodes = []
	if len(cNodes) > 1:
		for node in cNodes:
			nLocation = node.getLocation()
			cnBearing = calBearing(cent, nLocation)
			if isInRange(ccBearing, cnBearing):
				suitNodes.append(node)
	else:
		return cNodes
	return suitNodes

def getClusterInfo(c: Union[KmeansGroup, DepotInfo]):
	if isinstance(c, KmeansGroup):
		return c.getCentroid(), c.getAllNodes()
	if isinstance(c, DepotInfo):
		return c.getLocation(), [c]

def getClusterDis(c1: Union[KmeansGroup, DepotInfo], c2: Union[KmeansGroup, DepotInfo]):
#	 cent1, cent2 = c1.getCentroid(), c2.getCentroid()
#	 cNodes1, cNodes2 = c1.getAllNodes(), c2.getAllNodes()
	cent1, cNodes1 = getClusterInfo(c1)
	cent2, cNodes2 = getClusterInfo(c2)
	ccBearing1, ccBearing2 = calBearing(cent1, cent2), calBearing(cent2, cent1)
	suitNodes1 = getSuitNodes(cNodes1, cent1, ccBearing1)
	suitNodes2 = getSuitNodes(cNodes2, cent2, ccBearing2)
	return getMinDistance(suitNodes1, suitNodes2)

def chromo_create(_order):
	schedule = copy.deepcopy(_order)
	vehicle = list(np.random.randint(num_vehicles, size = (len(schedule))))
	np.random.shuffle(schedule)
	chromo = [schedule, vehicle]
	return chromo

def chromo_eval(_depotDistance, _allClientDistance, _chromo):
	route_set = get_route_set(_chromo)
	dist = 0
	for route in route_set:
		dist += cal_cost(_depotDistance, _allClientDistance, route)
	return dist,

def get_route_set(_chromo):
	route_set = [[] for _ in range(num_vehicles)]
	for nodes, vehicle in zip(_chromo[0], _chromo[1]):
		route_set[vehicle].append(nodes)
	return route_set

def cal_cost(_depotDistance, _allClientDistance, _route):
	if not _route:
		return 0
	dist = _depotDistance[_route[0]] + _depotDistance[_route[-1]]
	
	for node in range(len(_route) - 1):
		_curr = _route[node]
		_next = _route[node + 1]
		dist += _allClientDistance[_curr][_next]
	return dist

def crossover(_chromo1, _chromo2):
	cuts = get_chromo_cut()
	partial_crossover(_chromo1[0], _chromo2[0], cuts)
	
	cuts1 = get_chromo_cut()
	cuts2 = get_chromo_cut(cuts1[2])
	
	swap_genes(_chromo1[1], _chromo2[1], cuts1, cuts2)
	
def partial_crossover(_chromo1, _chromo2, cuts):
	size = len(_chromo1)
	part1, part2 = [0] * size, [0] * size
	
	for i in range(size):
		part1[_chromo1[i] - 1] = i
		part2[_chromo2[i] - 1] = i
	for i in range(cuts[0], cuts[1]):
		temp1 = _chromo1[i] - 1
		temp2 = _chromo2[i] - 1
		_chromo1[i], _chromo1[part1[temp2]] = temp2 + 1, temp1 + 1
		_chromo2[i], _chromo2[part2[temp1]] = temp1 + 1, temp2 + 1
		part1[temp1], part1[temp2] = part1[temp2], part1[temp1]
		part2[temp1], part2[temp2] = part2[temp2], part2[temp1]

def get_chromo_cut(cut_range = None, mutation = False):
	if mutation:
		randrange = best_result
	else:
		randrange = best_result + 1
	if cut_range is None:
		cut1 = rand.randrange(randrange)
		cut2 = rand.randrange(randrange)
		if cut1 > cut2:
			temp = cut2
			cut2 = cut1
			cut1 = temp
		cut_range = cut2 - cut1
	else:
		cut1 = rand.randrange(best_result + 1 - cut_range)
		cut2 = cut1 + cut_range
	return cut1, cut2, cut_range

def swap_genes(chromo1, chromo2, cuts1, cuts2):
	temp = chromo1[cuts1[0] : cuts1[1]]
	chromo1[cuts1[0] : cuts1[1]] = chromo2[cuts2[0] : cuts2[1]]
	chromo2[cuts2[0] : cuts2[1]] = temp

def mutation(_chromo):
	if np.random.rand() < 0.5:
		swap_gene(_chromo)
	else:
		shuffle_gene(_chromo)

def swap_gene(_chromo):
	cuts = get_chromo_cut(mutation = True)
	if np.random.rand() < 0.5:
		temp = _chromo[0][cuts[0]]
		_chromo[0][cuts[0]] = _chromo[0][cuts[1]]
		_chromo[0][cuts[1]] = temp
	else:
		temp = _chromo[1][cuts[0]]
		_chromo[1][cuts[0]] = _chromo[1][cuts[1]]
		_chromo[1][cuts[1]] = temp
		
def shuffle_gene(_chromo):
	cuts = get_chromo_cut(mutation = True)
	if np.random.rand() < 0.5:
		temp = _chromo[0][cuts[0]:cuts[1]]
		np.random.shuffle(temp)
		_chromo[0][cuts[0]:cuts[1]] = temp
	else:
		temp = _chromo[1][cuts[0]:cuts[1]]
		np.random.shuffle(temp)
		_chromo[1][cuts[0]:cuts[1]] = temp

def feasibility(_chromo):
	excess_payload = [vehicle_payload for i in range(num_vehicles)]
	_vehicle_id = [i for i in range(num_vehicles)]
	for i in range(num_vehicles):
		payload = 0
		nodes = [n for n in range(best_result) if _chromo[1][n] == i]
		for n in nodes:
			payload += demand_list[n]
		excess_payload[i] -= payload
	while any(_p < 0 for _p in excess_payload):
		v_id = next(i for i, _pl in enumerate(excess_payload) if _pl < 0)
		available_vehicles = [i for i, e in enumerate(excess_payload) if e > 0]
		if len(available_vehicles) == 0:
			raise('Infeasible solution')
		idx = [i for i, x in enumerate(_chromo[1]) if x == v_id]
		to_vehicle = rand.choice(available_vehicles)
		idx_to_move = rand.choice(idx)
		_chromo[1][idx_to_move] = to_vehicle
		demand = all_cluster[idx_to_move].getDemand()
		excess_payload[v_id] += demand
		excess_payload[to_vehicle] -= demand

def calClusterDemand(vrp, labels, num_of_cluster, payload):	
	for i in range(0, num_of_cluster):
		total_demand = 0
		indices = [j for j, x in enumerate(labels) if x == i]
		for j in indices:
			total_demand += vrp[j].getDemand()
			if total_demand > payload:
				return False
	return True


# In[3]:
vrp = {}

num_vehicles = int(sys.argv[2])
vehicle_payload = int(sys.argv[3])

# num_vehicles = 4
# vehicle_payload = 80

best_sse = 0
best_cluster = 0

num_population = 200
num_generations = 1000
prob_crossover = 0.4
prob_mutation = 0.6

url = sys.argv[1]
# url = "C:\\xampp\\htdocs\\fyp\\public\\storage\\csv\\abc_2023_03_12_23_35_17.csv"
# url = "C:\\xampp\\htdocs\\fyp\\public\\storage\\csv\\abc_2023_02_19_23_46_43.csv"

input_data = pd.read_csv(url, sep = ";", header = 0)

center = input_data.iloc[0, 0:14]
depot = DepotInfo(center['#'], center['delivery1'], center['lat'], center['lng'])

if len(input_data) < 2 :
	raise Exception('Too few data')

if int(center['demand']) > (num_vehicles * vehicle_payload):
	raise Exception('Not enough payload')


data = input_data.iloc[1:,0:14]
location_data = data.iloc[0:,8:10]

vrp['nodes'] = []
temp = []

all_cluster = []
all_cluster_num = []
client_nodes = {}
demand_list = []

depot_dis = []
all_client_dis = []

best_fit_list = []
best_sol_list = []

all_tuple_coor_list = []
mlrose_list = []
all_information = {}

final_path = []
output = {}


for index, row in data.iterrows():
	node = NodeInfo(row['#'], row['uuid'], row['sex'], row['first_name'], row['last_name'], row['phone_number'], row['delivery1'], row['delivery2'], row['lat'], row['lng'], row['demand'])
	temp.append(node.getLocation())
	vrp['nodes'].append(node)



# In[4]:

kmax = int((len(temp) / 2)) if (len(temp) % 2 == 0) else int((len(temp) + 1) / 2)

# kmax = 18

# for i in range(2, kmax + 1): #need imporve
for i in range(2, kmax + 1):
	km = KMeans(n_clusters = i)
	km_labels = km.fit_predict(temp)
	km_sse = silhouette_score(temp, km_labels, metric = 'euclidean')
	is_below_payload = calClusterDemand(vrp['nodes'], km_labels, i, vehicle_payload)
	if is_below_payload:
		if best_sse < km_sse:
			best_sse = km_sse
			best_km = km
			best_result = i
			best_labels = km_labels
			best_centroid = km.cluster_centers_

# In[5]:

for i in range(0, best_result):
	client_nodes[i] = []

for i in range(0, len(vrp['nodes'])):
	group = best_labels[i]
	client_nodes[group].append(vrp['nodes'][i])

for i in range(0, best_result):
	cluster_data = KmeansGroup(centroid = best_centroid[i], allNodes = client_nodes[i])
	all_cluster.append(cluster_data)
	all_cluster_num.append(i)
demand_list = [i.getDemand() for i in all_cluster]

# In[6]:

for i in range(0, best_result):
	cluster = all_cluster[i]
	depot_dis.append(getClusterDis(depot, cluster))
	
for i in range(0, best_result):
	c1 = all_cluster[i]
	temp = []
	for j in range(0, best_result):
		c2 = all_cluster[j]
		result = getClusterDis(c1, c2)
		temp.append(result)
	all_client_dis.append(temp)

# In[7]:

tb = base.Toolbox()

creator.create('FitnessMin', base.Fitness, weights = (-1.0,))
creator.create('Individual', list, fitness = creator.FitnessMin)

# In[13]:

tb.register('indexes', chromo_create, all_cluster_num)
tb.register('individual', tools.initIterate, creator.Individual, tb.indexes)
tb.register('population', tools.initRepeat, list, tb.individual)
tb.register('evaluate', chromo_eval, depot_dis, all_client_dis)
tb.register('select', tools.selTournament)
tb.register('mate', crossover)
tb.register('mutate', mutation)
tb.register('feasibility', feasibility)

# In[15]:

population = tb.population(n = num_population)

# In[16]:

fitness_set = list(tb.map(tb.evaluate, population))
for ind, fit in zip(population, fitness_set):
	ind.fitness.values = fit

# In[17]:

best_fit = math.inf
for gen in range(0, num_generations):
	# if(gen % 50 == 0):
	# 	print(f'Generation: {gen:4} | Fitness: {best_fit: .2f}')
	offspring = tb.select(population, len(population), tournsize = 3)
	offspring = list(map(tb.clone, offspring))
	for child1, child2 in zip(offspring[0::2], offspring[1::2]):
		if np.random.random() < prob_crossover:
			tb.mate(child1, child2)
			del child1.fitness.values
			del child2.fitness.values
	for chromo in offspring:
		if np.random.random() < prob_mutation:
			tb.mutate(chromo)
			del chromo.fitness.values
	for chromo in offspring:
		tb.feasibility(chromo)
	invalid_ind = [ind for ind in offspring if not ind.fitness.valid]
	fitness_set = map(tb.evaluate, invalid_ind)
	for ind, fit in zip(invalid_ind, fitness_set):
		ind.fitness.values = fit
	population[:] = offspring
	curr_best_sol = tools.selBest(population, 1)[0]
	curr_best_fit = curr_best_sol.fitness.values[0]
	
	if curr_best_fit < best_fit:
		best_sol = curr_best_sol
		best_fit = curr_best_fit
	best_fit_list.append(best_fit)
	best_sol_list.append(best_sol)


# In[18]:

best_route = get_route_set(best_sol)

# In[]:

count = 0

for i in range(0, len(best_route)):
	coor_list = []
	tuple_coor_list = []
	this_information = []
	if(not best_route[i]):
		continue
	for j in best_route[i]:
		coor_list = coor_list + all_cluster[j].getAllNodes()
	for j in coor_list:
		location = j.getLocation()
		tuple_coor_list.append(tuple(location))
		this_information.append(j.getInfo())
	all_information[count] = this_information
	count += 1
	all_tuple_coor_list.append(tuple_coor_list)
	mlrose_list.append(mlrose.TSPOpt(length = len(tuple_coor_list), coords = tuple_coor_list, maximize=False))

# In[]:

for i in mlrose_list:
	temp_result = mlrose.genetic_alg(i, random_state = 2)
	final_path.append(temp_result[0])

# In[]:

for f in range(0, len(final_path)):
	curr_path = []
	route = final_path[f]
	route_information = all_information[f]
	for p in range(0, len(route)):
		curr_path.append(route_information[route[p]])
	output[str(f)] = curr_path

print(output)