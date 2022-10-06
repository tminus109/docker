/*
    Taxi App

    Car {
        id: string
        name: string
        licenseNumber: string
        hourlyRate: number,
        trips: Array<Trip>
    }

    Trip {
        numberOfMinutes: number
        date: number
    }
*/

const express = require("express");
const app = express();
const ObjectId = require("mongodb").ObjectID;

function getClient() {
  const MongoClient = require("mongodb").MongoClient;
  const uri = process.env.DB_CONNECTION;
  return new MongoClient(uri, { useNewUrlParser: true, useUnifiedTopology: true });
}

const path = require("path");

app.get("/", function (req, res) {
  res.sendFile(path.join(__dirname, "views", "home.html"));
});

app.use(express.static(path.join(__dirname, "public")));

app.get("/cars", function (req, res) {
  const client = getClient();

  client.connect(async (err) => {
    // console.log(err);

    if(err) {
      res.status(500);
      res.send([]);
      return;
    }

    const collection = client.db("taxi_app").collection("cars");
    const cars = await collection.find().toArray();
    res.send(cars);
  });

});

function getId(raw) {
  try {
    return new ObjectId(raw);
  } catch (err) {
    return "";
  }
}

app.get("/cars/:id", function (req, res) {
  const id = getId(req.params.id);
  if (!id) {
    res.send({ error: "invalid id" });
    return;
  }

  const client = getClient();
  client.connect(async (err) => {
    if(err) {
      res.status(500);
      res.send({});
      return;
    }
    const collection = client.db("taxi_app").collection("cars");
    const car = await collection.findOne({ _id: id });
    if (!car) {
      res.send({ error: "not found" });
      return;
    }
    res.send(car);
  });
});

app.delete("/cars/:id", function (req, res) {
  const id = getId(req.params.id);
  if (!id) {
    res.send({ error: "invalid id" });
    return;
  }

  const client = getClient();
  client.connect(async (err) => {
    if(err) {
      res.status(500);
      res.send({});
      return;
    }
    const collection = client.db("taxi_app").collection("cars");
    const result = await collection.deleteOne({ _id: id });
    if (!result.deletedCount) {
      res.send({ error: "not found" });
      return;
    }
    res.send({ id: req.params.id });
  });
});

const bodyParser = require("body-parser");

app.put("/cars/:id", bodyParser.json(), function (req, res) {
  const updatedCar = {
    name: req.body.name,
    licenseNumber: req.body.licenseNumber,
    hourlyRate: req.body.hourlyRate,
  };

  const id = getId(req.params.id);
  if (!id) {
    res.send({ error: "invalid id" });
    return;
  }

  const client = getClient();
  client.connect(async (err) => {
    if(err) {
      res.status(500);
      res.send({});
      return;
    }
    const collection = client.db("taxi_app").collection("cars");
    const result = await collection.findOneAndUpdate({ _id: id }, { $set: updatedCar });

    if (!result.ok) {
      res.send({ error: "not found" });
      return;
    }
    res.send(result.value);
  });
});

app.post("/cars", bodyParser.json(), function (req, res) {
  const newCar = {
    name: req.body.name,
    licenseNumber: req.body.licenseNumber,
    hourlyRate: req.body.hourlyRate,
    trips: [],
  };

  const client = getClient();
  client.connect(async (err) => {
    if(err) {
      res.status(500);
      res.send({});
      return;
    }
    const collection = client.db("taxi_app").collection("cars");
    const result = await collection.insertOne(newCar);
    if (!result.insertedCount) {
      res.send({ error: "insert error" });
      return;
    }
    res.send(newCar);
  });
});

app.post("/trips", bodyParser.json(), function (req, res) {
  const newTrip = {
    numberOfMinutes: req.body.numberOfMinutes,
    date: Math.floor(Date.now() / 1000),
  };

  const id = getId(req.body.carId);
  if (!id) {
    res.send({ error: "invalid id" });
    return;
  }

  const client = getClient();
  client.connect(async (err) => {
    if(err) {
      res.status(500);
      res.send({});
      return;
    }
    const collection = client.db("taxi_app").collection("cars");
    const result = await collection.findOneAndUpdate(
      { _id: id },
      { $push: { trips: newTrip } },
      { returnOriginal: false }
    );

    if (!result.ok) {
      res.send({ error: "not found" });
      return;
    }
    res.send(result.value);
  });
});

app.listen(3000);
