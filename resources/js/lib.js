function tspBranchBound(distances) {
    const n = distances.length;
    let bestPath = null;
    let bestCost = Infinity;

    // Stack to store nodes during exploration
    const stack = [{ city: 0, path: [0], visited: new Set([0]), cost: 0 }];

    while (stack.length > 0) {
        const node = stack.pop();
        if (node.path.length === n) {
            // All cities visited, calculate total cost including returning to start
            const cost = node.cost + distances[node.path[n - 1]][0];
            if (cost < bestCost) {
                bestPath = node.path;
                bestCost = cost;
            }
        } else {
            // Generate child nodes for unvisited cities
            const unvisited = new Set(
                Array.from({ length: n }, (_, i) => i)
            ).difference(node.visited);

            for (const city of unvisited) {
                const childPath = [...node.path, city];
                const childCost =
                    node.cost +
                    distances[node.path[node.path.length - 1]][city];

                // Prune nodes with cost exceeding current best
                if (childCost < bestCost) {
                    stack.push({
                        city,
                        path: childPath,
                        visited: new Set(node.visited).add(city),
                        cost: childCost,
                    });
                }
            }
        }
    }

    return [bestPath, bestCost];
}

function initBranchBound(nodes, currentPosition) {
    console.log("Init Calculation...");

    let startNode = {
        idDelivery: 0,
        orderID: "Lokasi saat ini",
        latitude: currentPosition.lat,
        longitude: currentPosition.long,
    };

    const cities = [startNode, ...nodes];
    // Create an empty distances matrix
    const distances = new Array(cities.length)
        .fill(null)
        .map(() => new Array(cities.length).fill(0));

    // Calculate and populate distances matrix
    for (let i = 0; i < cities.length; i++) {
        for (let j = i + 1; j < cities.length; j++) {
            const distance = haversineDistance(
                cities[i].latitude,
                cities[i].longitude,
                cities[j].latitude,
                cities[j].longitude
            );
            // distance is symmetric
            distances[i][j] = distance;
            distances[j][i] = distance;
        }
    }

    const [bestPath, bestCost] = tspBranchBound(distances);

    let result = bestPath.map((i) => cities[i]);
    result.push(startNode);
    return result;
}
function toRadian(angle) {
    return angle * (Math.PI / 180);
}
function haversineDistance(latitude, longitude, shopLatitude, shopLongitude) {
    // Convert degrees to radians
    const radiansLat1 = toRadian(latitude);
    const radiansLat2 = toRadian(shopLatitude);
    const radiansLon1 = toRadian(longitude);
    const radiansLon2 = toRadian(shopLongitude);

    const dLat = radiansLat2 - radiansLat1;
    const dLon = radiansLon2 - radiansLon1;

    // Earth's radius in kilometers
    const earthRadius = 6371;

    const d =
        earthRadius *
        2 *
        Math.asin(
            Math.sqrt(
                (1 -
                    Math.cos(dLat) +
                    Math.cos(radiansLat1) *
                        Math.cos(radiansLat2) *
                        (1 - Math.cos(dLon))) /
                    2
            )
        );

    return Math.round(d);
}
