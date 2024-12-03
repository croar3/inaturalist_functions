Still in progress. Goal is: given a taxon ID, show bar graphs for each state's activity for the taxon, in order to see what months the taxon is most active.

Currently it is broken - The iNat API croaks on me after 3 states. Even with delaying each API call 0.5 seconds, the API still fails pretty quickly. The first 3 states tend to work fine. 

There has to be a better way to do this than making 600 API calls. You could add one check to see if any records exist in the state as a whole before checking each month in a potentially empty state, but that would only make it marginally better unless it is a rare taxon.
