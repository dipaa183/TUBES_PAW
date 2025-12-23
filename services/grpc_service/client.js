const grpc = require('@grpc/grpc-js');
const protoLoader = require('@grpc/proto-loader');
const path = require('path');

const PROTO_PATH = path.join(__dirname, 'pricing.proto');
const packageDefinition = protoLoader.loadSync(PROTO_PATH, {
    keepCase: true,
    longs: String,
    enums: String,
    defaults: true,
    oneofs: true
});

const pricingProto = grpc.loadPackageDefinition(packageDefinition).pricing;

function main() {
    const client = new pricingProto.PricingService('localhost:50051', grpc.credentials.createInsecure());

    const request = {
        pages: 50,
        type: 'color'
    };

    console.log(`Sending request: ${request.pages} pages, type: ${request.type}`);

    client.CalculatePrice(request, (err, response) => {
        if (err) {
            console.error(err);
        } else {
            console.log('Total Price:', response.total_price);
        }
    });

    const requestBW = {
        pages: 100,
        type: 'bw'
    };

    console.log(`Sending request: ${requestBW.pages} pages, type: ${requestBW.type}`);
    client.CalculatePrice(requestBW, (err, response) => {
        if (err) {
            console.error(err);
        } else {
            console.log('Total Price:', response.total_price);
        }
    });
}

main();
