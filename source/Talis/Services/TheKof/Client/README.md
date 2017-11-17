The house of CLient(s)
======================

Each asset/element type has it's own private client.  
This structure helps constraint the code to use the right hierarchies $Client->surveys()->collectors() ... vs $Client->collectors()->surveys()
and helps with the Model generation for each asset.
