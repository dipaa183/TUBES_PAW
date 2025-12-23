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

function calculatePrice(call, callback) {
    const pages = call.request.pages;
    const type = call.request.type;
    let pricePerPage = 0; // Default

    console.log(`Received request: ${pages} pages, type: ${type}`);

    if (type === 'color') {
        pricePerPage = 1000;
    } else if (type === 'bw') {
        pricePerPage = 250; // Black & White
    }

    const totalPrice = pages * pricePerPage;
    callback(null, { total_price: totalPrice });
}

function main() {
    const server = new grpc.Server();
    server.addService(pricingProto.PricingService.service, { CalculatePrice: calculatePrice });
    server.bindAsync('127.0.0.1:50051', grpc.ServerCredentials.createInsecure(), (err, port) => {
        if (err) {
            console.error(err);
            return;
        }
        console.log(`gRPC Server running at http://127.0.0.1:${port}`);
    });
}

main();
