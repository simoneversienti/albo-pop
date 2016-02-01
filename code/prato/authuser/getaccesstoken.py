import webbrowser

import tweepy

from ConfigParser import ConfigParser

cp=ConfigParser()
cp.read('alboPretorio.cfg')

consumer_key=cp.get('twitter','CONSUMER_KEY')
consumer_secret=cp.get('twitter','CONSUMER_SECRET')

"""
    Query the user for their consumer key/secret
    then attempt to fetch a valid access token.
"""

if __name__ == "__main__":

    auth = tweepy.OAuthHandler(consumer_key, consumer_secret)

    # Open authorization URL in browser
    print "authorize at ", auth.get_authorization_url()

    # Ask user for verifier pin
    pin = raw_input('Verification pin number from twitter.com: ').strip()

    # Get access token
    token = auth.get_access_token(verifier=pin)

    print "token:", token

    # Give user the access token
    print 'Access token:'
    print '  Key: %s' % token[0]
    print '  Secret: %s' % token[1]

