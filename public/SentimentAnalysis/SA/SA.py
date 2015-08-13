# -*- coding: utf-8 -*-
"""
basic_sentiment_analysis
~~~~~~~~~~~~~~~~~~~~~~~~

This module contains the code and examples described in 
http://fjavieralba.com/basic-sentiment-analysis-with-python.html

"""

from pprint import pprint
import nltk
import yaml
import sys
import os
import re

class Splitter(object):

    def __init__(self):
        self.nltk_splitter = nltk.data.load('tokenizers/punkt/english.pickle')
        self.nltk_tokenizer = nltk.tokenize.TreebankWordTokenizer()

    def split(self, text):
        """
        input format: a paragraph of text
        output format: a list of lists of words.
            e.g.: [['this', 'is', 'a', 'sentence'], ['this', 'is', 'another', 'one']]
        """
        sentences = self.nltk_splitter.tokenize(text)
        tokenized_sentences = [self.nltk_tokenizer.tokenize(sent) for sent in sentences]
        return tokenized_sentences


class POSTagger(object):

    def __init__(self):
        pass
        
    def pos_tag(self, sentences):
        """
        input format: list of lists of words
            e.g.: [['this', 'is', 'a', 'sentence'], ['this', 'is', 'another', 'one']]
        output format: list of lists of tagged tokens. Each tagged tokens has a
        form, a lemma, and a list of tags
            e.g: [[('this', 'this', ['DT']), ('is', 'be', ['VB']), ('a', 'a', ['DT']), ('sentence', 'sentence', ['NN'])],
                    [('this', 'this', ['DT']), ('is', 'be', ['VB']), ('another', 'another', ['DT']), ('one', 'one', ['CARD'])]]
        """

        pos = [nltk.pos_tag(sentence) for sentence in sentences]
        #adapt format
        pos = [[(word, word, [postag]) for (word, postag) in sentence] for sentence in pos]
        return pos

class DictionaryTagger(object):

    def __init__(self, dictionary_paths):
        files = [open(path, 'r') for path in dictionary_paths]
        dictionaries = [yaml.load(dict_file) for dict_file in files]
        map(lambda x: x.close(), files)
        self.dictionary = {}
        self.max_key_size = 0
        for curr_dict in dictionaries:
            for key in curr_dict:
                if key in self.dictionary:
                    self.dictionary[key].extend(curr_dict[key])
                else:
                    self.dictionary[key] = curr_dict[key]
                    self.max_key_size = max(self.max_key_size, len(key))

    def tag(self, postagged_sentences):
        return [self.tag_sentence(sentence) for sentence in postagged_sentences]

    def tag_sentence(self, sentence, tag_with_lemmas=False):
        """
        the result is only one tagging of all the possible ones.
        The resulting tagging is determined by these two priority rules:
            - longest matches have higher priority
            - search is made from left to right
        """
        tag_sentence = []
        N = len(sentence)
        if self.max_key_size == 0:
            self.max_key_size = N
        i = 0
        while (i < N):
            j = min(i + self.max_key_size, N) #avoid overflow
            tagged = False
            while (j > i):
                expression_form = ' '.join([word[0] for word in sentence[i:j]]).lower()
                expression_lemma = ' '.join([word[1] for word in sentence[i:j]]).lower()
                if tag_with_lemmas:
                    literal = expression_lemma
                else:
                    literal = expression_form
                if literal in self.dictionary:
                    #self.logger.debug("found: %s" % literal)
                    is_single_token = j - i == 1
                    original_position = i
                    i = j
                    taggings = [tag for tag in self.dictionary[literal]]
                    tagged_expression = (expression_form, expression_lemma, taggings)
                    if is_single_token: #if the tagged literal is a single token, conserve its previous taggings:
                        original_token_tagging = sentence[original_position][2]
                        tagged_expression[2].extend(original_token_tagging)
                    tag_sentence.append(tagged_expression)
                    tagged = True
                else:
                    j = j - 1
            if not tagged:
                tag_sentence.append(sentence[i])
                i += 1
        return tag_sentence

def value_of(sentiment,  emo_to_analysis):
    if sentiment == emo_to_analysis: return 1
    return 0

def sentence_score(sentence_tokens, previous_token, acum_score, opp_score, emo_to_analysis):   
    if not sentence_tokens:
        return (acum_score, opp_score)
    else:
        current_token = sentence_tokens[0]
        tags = current_token[2]
        token_score = sum([value_of(tag, emo_to_analysis) for tag in tags])
        opp_token_score = 0
        if previous_token is not None:
            previous_tags = previous_token[2]
            if 'inc' in previous_tags:
                token_score *= 2
            elif 'dec' in previous_tags:
                token_score /= 2
            elif 'inv' in previous_tags:
                token_score *= -1
                if token_score != 0:
                    opp_token_score += 1
        return sentence_score(sentence_tokens[1:], current_token, acum_score + token_score, opp_score + opp_token_score, emo_to_analysis)

def sentiment_score(review, emo_to_analysis):
    score_current_emo   = 0
    score_opp_emo       = 0
    for sentence in review:
        # print(sentence)
        temp_score_emo, temp_score_opp = sentence_score(sentence, None, 0, 0, emo_to_analysis)
        score_current_emo += temp_score_emo
        score_opp_emo += temp_score_opp

    return (score_current_emo, score_opp_emo)

    # return sum([sentence_score(sentence, None, 0.0, emo_to_analysis, opposite_emo) for sentence in review])

if __name__ == "__main__":
    joy_score           = 0
    sadness_score       = 0
    trust_score         = 0
    disgust_score       = 0
    fear_score          = 0
    anger_score         = 0
    surprise_score      = 0
    anticipation_score  = 0

    text = ""
    for i in range(1, len(sys.argv)):
        text = text + sys.argv[i] + ' '
    # text = """trust, disgust, fear, anger, surprise, anticipation, joy, sadness."""

    splitter = Splitter()
    postagger = POSTagger()
    dicttagger = DictionaryTagger([ os.path.dirname(os.path.abspath(__file__))+'/dicts/joy.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/sadness.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/trust.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/disgust.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/fear.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/anger.yml',
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/surprise.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/anticipation.yml',  
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/inc.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/dec.yml', 
                                    os.path.dirname(os.path.abspath(__file__))+'/dicts/inv.yml'
                                 ])

    # dicttagger = DictionaryTagger([ 'dicts/positive.yml', 
    #                                 'dicts/negative.yml', 
    #                                 'dicts/inc.yml', 
    #                                 'dicts/dec.yml', 
    #                                 'dicts/inv.yml'
    #                              ])

    splitted_sentences = splitter.split(text)
    # pprint(splitted_sentences)

    pos_tagged_sentences = postagger.pos_tag(splitted_sentences)
    # pprint(pos_tagged_sentences)

    dict_tagged_sentences = dicttagger.tag(pos_tagged_sentences)
    # pprint(dict_tagged_sentences)

    
    temp1, temp2 = sentiment_score(dict_tagged_sentences, "joy")
    joy_score += temp1
    sadness_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "sadness")
    sadness_score += temp1
    joy_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "trust")
    trust_score += temp1
    disgust_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "disgust")
    disgust_score += temp1
    trust_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "fear")
    fear_score += temp1
    anger_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "anger")
    anger_score += temp1
    fear_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "surprise")
    surprise_score += temp1
    anticipation_score += temp2

    temp1, temp2 = sentiment_score(dict_tagged_sentences, "anticipation")
    anticipation_score += temp1
    surprise_score += temp2

    # print("analyzing sentiment...")
    print(joy_score)
    print(sadness_score)
    print(trust_score)
    print(disgust_score)
    print(fear_score)
    print(anger_score)
    print(surprise_score)
    print(anticipation_score)


