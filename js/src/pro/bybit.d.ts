import bybitRest from '../bybit.js';
import { Int, OHLCV } from '../base/types.js';
import Client from '../base/ws/Client.js';
export default class bybit extends bybitRest {
    describe(): any;
    requestId(): any;
    getUrlByMarketType(symbol?: string, isPrivate?: boolean, method?: any, params?: {}): any;
    cleanParams(params: any): any;
    watchTicker(symbol: string, params?: {}): Promise<any>;
    watchTickers(symbols?: string[], params?: {}): Promise<any>;
    handleTicker(client: Client, message: any): void;
    watchOHLCV(symbol: string, timeframe?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    watchOHLCVForSymbols(symbolsAndTimeframes: string[][], since?: Int, limit?: Int, params?: {}): Promise<import("../base/types.js").Dictionary<import("../base/types.js").Dictionary<OHLCV[]>>>;
    handleOHLCV(client: Client, message: any): void;
    parseWsOHLCV(ohlcv: any, market?: any): OHLCV;
    watchOrderBook(symbol: string, limit?: Int, params?: {}): Promise<any>;
    watchOrderBookForSymbols(symbols: string[], limit?: Int, params?: {}): Promise<any>;
    handleOrderBook(client: Client, message: any): void;
    handleDelta(bookside: any, delta: any): void;
    handleDeltas(bookside: any, deltas: any): void;
    watchTrades(symbol: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    watchTradesForSymbols(symbols: string[], since?: Int, limit?: Int, params?: {}): Promise<any>;
    handleTrades(client: Client, message: any): void;
    parseWsTrade(trade: any, market?: any): import("../base/types.js").Trade;
    getPrivateType(url: any): "spot" | "unified" | "usdc";
    watchMyTrades(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    handleMyTrades(client: Client, message: any): void;
    watchPositions(symbols?: string[], since?: Int, limit?: Int, params?: {}): Promise<any>;
    setPositionsCache(client: Client, symbols?: string[]): any;
    loadPositionsSnapshot(client: any, messageHash: any): Promise<void>;
    handlePositions(client: any, message: any): void;
    watchOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    handleOrder(client: Client, message: any, subscription?: any): void;
    parseWsSpotOrder(order: any, market?: any): import("../base/types.js").Order;
    watchBalance(params?: {}): Promise<any>;
    handleBalance(client: Client, message: any): void;
    parseWsBalance(balance: any, accountType?: any): void;
    watchTopics(url: any, messageHash: any, topics: any, subscriptionHash: any, params?: {}): Promise<any>;
    authenticate(url: any, params?: {}): Promise<any>;
    handleErrorMessage(client: Client, message: any): boolean;
    handleMessage(client: Client, message: any): void;
    ping(client: any): {
        req_id: any;
        op: string;
    };
    handlePong(client: Client, message: any): any;
    handleAuthenticate(client: Client, message: any): any;
    handleSubscriptionStatus(client: Client, message: any): any;
}
