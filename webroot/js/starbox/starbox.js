//  Starbox 0.3.0.4 - 17-12-2007

//  Copyright (c) 2007 Nick Stakenburg (http://www.nickstakenburg.com)
//
//  Permission is hereby granted, free of charge, to any person obtaining
//  a copy of this software and associated documentation files (the
//  "Software"), to deal in the Software without restriction, including
//  without limitation the rights to use, copy, modify, merge, publish,
//  distribute, sublicense, and/or sell copies of the Software, and to
//  permit persons to whom the Software is furnished to do so, subject to
//  the following conditions:
//
//  The above copyright notice and this permission notice shall be
//  included in all copies or substantial portions of the Software.
//
//  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
//  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
//  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
//  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
//  CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
//  TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
//  SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

//  More information on this project:
//  http://www.nickstakenburg.com/projects/starbox/

var Starboxes = {
  // Configuration for all starboxes
  inverse: false,
  locked: false,
  onRate: Prototype.emptyFunction,
  overlayImages: '../../img/starbox/', // relative to starbox.js
  overlay: 'default.png',
  rerate: false,

  REQUIRED_Prototype: '1.6.0',
  REQUIRED_Scriptaculous: '1.8.0',

  load: function() {
    this.require('Prototype');
    var srcMatch = /starbox\.js$/;
    this.imageSource = (($$("head script[src]").find(function(s) {
      return s.src.match(srcMatch);
    }) || {}).src || '').replace(srcMatch, '') + this.overlayImages;
  },

  require: function(library) {
    if ((typeof window[library] == 'undefined') ||
      (this.convertVersionString(window[library].Version) < this.convertVersionString(this['REQUIRED_' + library])))
      throw('Starbox requires ' + library + ' >= ' + this['REQUIRED_' + library]);
  },

  convertVersionString: function(versionString) {
    var r = versionString.split('.');
    return parseInt(r[0])*100000 + parseInt(r[1])*1000 + parseInt(r[2]);
  },

  fixIE: (function(agent) {
    var version = new RegExp('MSIE ([\\d.]+)').exec(agent);
    return version ? (parseFloat(version[1]) <= 6) : false;
  })(navigator.userAgent),

  imagecache: [],
  cacheImage: function(imageInfo) {
    if(!this.getCachedImage(imageInfo.src)) this.imagecache.push(imageInfo);
    return imageInfo;
  },

  getCachedImage: function(src) {
    return this.imagecache.find(function(imageInfo) { return imageInfo.src == src });
  },

  // speed up the initial load of the page by building in batches
  // images are cached to to minimize requests
  buildQueue: [],
  queueBuild: function(starbox) {
    this.buildQueue.push(starbox);
  },

  processBuildQueue: function() {
    // on empty queue, stop loading as batches
    if (!this.buildQueue[0]) { this.batchLoading = true; return; }
    this.cacheBuildBatch(this.buildQueue[0]);
  },

  cacheBuildBatch: function(starbox) {
    var set = [];
    var overlay = starbox.options.overlay;
    var imageInfo = this.getCachedImage(overlay);

    // create a batch based on images with the same overlay
    this.buildQueue.each(function(s) {
      if (s.options.overlay == overlay) {
        set.push(s);
        this.buildQueue = this.buildQueue.without(s);
      }
    }.bind(this));

    if (!imageInfo) {
      var starImage = new Image();
      starImage.onload=function() {
        var imageInfo = this.cacheImage({ src: overlay, height: starImage.height,
          width: starImage.width, fullsrc: starImage.src });
        this.buildBatch(set, imageInfo);
      }.bind(this);
      starImage.src = Starboxes.imageSource + overlay;
    }
    else { this.buildBatch(set, imageInfo); }
  },

  buildBatch: function(set, imageInfo) {
    set.each(function(s) {
      s.imageInfo = imageInfo;
      s.build();
    });
    this.processBuildQueue();
  }
};
Starboxes.load();
document.observe('dom:loaded', Starboxes.processBuildQueue.bind(Starboxes));

var Starbox = Class.create({
  initialize: function(element, average) {
    this.element = $(element),
    this.average = average;

    this.options = Object.extend({
      buttons: 5,
      className : 'default',
      color: false,
      duration: 0.6,
      effect: { mouseover: false , mouseout: (window.Effect && Effect.Morph) },
      hoverColor: false,
      hoverClass: 'hover',
      ghostColor: false,
      ghosting: false,
      ratedClass: 'rated',
      identity: false,
      indicator: false,
      inverse: Starboxes.inverse,
      locked: false,
      max: 5,
      onRate: Starboxes.onRate,
      rerate: Starboxes.rerate,
      rated: false,
      overlay: Starboxes.overlay,
      stars: 5,
      total : 0
    }, arguments[2] || {});

    this.rated = this.options.rated;
    this.total = this.options.total;
    this.locked =  this.options.locked || (this.rated && !this.options.rerate);

    if (this.options.effect && (this.options.effect.mouseover || this.options.effect.mouseout))
      Starboxes.require('Scriptaculous');

    Starboxes.queueBuild(this);
    if (Starboxes.batchLoading) Starboxes.processBuildQueue();
  },

  enable: function() {
    if (!Prototype.Browser.IE) {
      this.onMouseout = this.onMouseout.wrap(function(proceed, event) {
        var rel = event.relatedTarget, cur = event.currentTarget;
        if (rel && rel.nodeType == Node.TEXT_NODE) rel = rel.parentNode;
        if (rel && rel != cur && !(rel.descendantOf(cur)))
          proceed(event);
      });
    }

    $w('mouseout mouseover click').each(function(e) {
      var E = e.capitalize();
      this['on' + E + '_cached'] = this['on' + E].bindAsEventListener(this);
      this.starbar.observe(e, this['on' + E + '_cached']);
    }.bind(this));

    this.buttons.invoke('setStyle', { cursor: 'pointer' });
  },

  disable: function() {
    $w('mouseover mouseout click').each(function(e) {
      this.starbar.stopObserving(e, this['on' + e.capitalize() + '_cached']);
    }.bind(this));

    this.buttons.invoke('setStyle', { cursor: 'auto' });
  },

  build: function() {
    this.starWidth = this.imageInfo.width;
    this.starHeight = this.imageInfo.height;
    this.starSrc = this.imageInfo.fullsrc;
    this.boxWidth = this.starWidth * this.options.stars;
    this.buttonWidth = this.boxWidth / this.options.buttons;
    this.buttonRating = this.options.max / this.options.buttons;

    if(this.options.effect) {
      this.zeroPosition = this.getBarPosition(0);
      this.maxPosition = this.getBarPosition(this.options.max);
    }

    var styles = {
      absolute: { position: 'absolute', top: 0, left: 0, width: this.boxWidth + 'px', height: this.starHeight + 'px' },
      base: { position: 'relative', width: this.boxWidth + 'px', height: this.starHeight + 'px' },
      star: { position: 'absolute', top: 0, left: 0, width: this.starWidth + 'px', height: this.starHeight + 'px' }
    };

    this.element.addClassName('starbox');
    this.container = new Element('div', { 'class': this.options.className || '' }).setStyle({ position: 'relative' });

    this.status = this.container.appendChild(new Element('div'));
    if (this.rated) this.status.addClassName('rated');
    if (this.locked) this.status.addClassName('locked');

    this.hover = this.status.appendChild(new Element('div'));
    this.wrapper = this.hover.appendChild(new Element('div', { 'class': 'stars' }));
    this.wrapper.setStyle(Object.extend({ overflow: 'hidden' }, styles.base));

    if (this.options.ghosting) {
      this.ghost = this.wrapper.appendChild(new Element('div', { 'class': 'ghost' }).setStyle(styles.absolute));
      if (this.options.ghostColor) this.ghost.setStyle({ background: this.options.ghostColor });
      if (this.options.effect) this.ghost.scope = this.ghost.identify();
      this.setBarPosition(this.ghost, this.average, (window.Effect && Effect.Morph));
    }

    this.colorbar = this.wrapper.appendChild(new Element('div', { 'class': 'colorbar' }).setStyle(styles.absolute));
    if (this.options.color) this.colorbar.setStyle({ background: this.options.color });
    if (this.options.effect) this.colorbar.scope = this.colorbar.identify();

    var starWrapper = this.wrapper.appendChild(new Element('div').setStyle(styles.absolute));
    this.starbar = starWrapper.appendChild(new Element('div').setStyle(styles.base));

    this.options.stars.times(function(i) {
      var star = this.starbar.appendChild(new Element('div').setStyle(Object.extend({
        background: 'url(' + this.starSrc + ') top left no-repeat'
      }, styles.star)));
      star.setStyle({ left: this.starWidth * i + 'px' });

      if (Starboxes.fixIE) {
        star.setStyle({
          background: 'none', 'filter' : 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' +
            this.starSrc + '\'\', sizingMethod=\'scale\')'
        });
      }
    }.bind(this));

    this.buttons = [];
    this.options.buttons.times(function(i) {
      var leftPos = this.options.inverse ? this.boxWidth - this.buttonWidth * (i + 1) : this.buttonWidth * i;
      var button = this.starbar.appendChild(new Element('div', { href: 'javascript:;' }).setStyle({
        position: 'absolute',
        top: 0,
        left: leftPos + 'px',
        width: this.buttonWidth + (Prototype.Browser.IE ? 1 : 0) + 'px',
        height: this.starHeight + 'px'
      }));
      button.rating = this.buttonRating * i + this.buttonRating;
      this.buttons.push(button);
    }.bind(this));

    this.setBarPosition(this.colorbar, this.average);
    this.element.update(this.container);

    if (this.options.indicator) {
      this.indicator = this.hover.appendChild(new Element('div', { 'class' : 'indicator' }));
      this.updateIndicator();
    }

    if (!this.locked) this.enable();
  },

  updateAverage: function(increment) {
    if (this.rated && this.options.rerate)
      this.average = (this.total * this.average - this.rated) / (this.total-1 || 1);

    var total = this.rated ? this.total : this.total++;

    this.average = (this.average == 0) ? increment :
      (this.average * (this.rated ? total-1 : total) + increment) / (this.rated ? total : total+1);
  },

  updateIndicator: function() {
    this.indicator.update(new Template(this.options.indicator).evaluate({
      max: this.options.max,
      total: this.total,
      average: (this.average * 10).round() / 10
    }));
  },

  getBarPosition : function(rating) {
    var position = (this.boxWidth - (rating/this.buttonRating) * this.buttonWidth);
    return parseInt(this.options.inverse ? position.ceil() : -1 * position.floor());
  },

  setBarPosition: function(element, rating) {
    if (this.options.effect && this['activeEffect_' + element.scope])
      Effect.Queues.get(element.scope).remove(this['activeEffect_' + element.scope]);

    var left = this.getBarPosition(rating);
    if (arguments[2]) {
      var current = parseInt(element.getStyle('left'));
      var to = this.getBarPosition(rating);
      if (current == to) return;
      var mspeed = ((this.maxPosition - (current - to).abs()).abs() / this.zeroPosition.abs()).toFixed(2);

      this['activeEffect_' + element.scope] = new Effect.Morph(element, { style: { left: left + 'px' },
        queue: { position: 'end', limit: 1, scope: element.scope}, duration: (this.options.duration * mspeed) });
    }
    else { element.setStyle({ left: left + 'px' }); }
  },

  onClick: function(event) {
    var element = event.element();
    if (!element.rating) return;

    this.updateAverage(element.rating);
    if (this.options.indicator) this.updateIndicator();
    if (this.options.ghosting) this.setBarPosition(this.ghost, this.average, (window.Effect && Effect.Morph));

    if (!this.rated) this.status.addClassName('rated');
    var rerated = !!this.rated;
    this.rated = element.rating;

    if (!this.options.rerate) {
      this.disable();
      this.status.addClassName('locked');
      this.onMouseout(event);
    }

    var info = {
      average: this.average,
      identity: this.options.identity,
      max: this.options.max,
      rated: element.rating,
      rerated: rerated,
      total: this.total
    };
    this.options.onRate(this.element, info);
    this.element.fire('starbox:rated');
  },

  onMouseout: function(event) {
    this.setBarPosition(this.colorbar, this.average, (this.options.effect && this.options.effect.mouseout));
    this.hovered = false;
    if (this.options.hoverClass) this.hover.removeClassName(this.options.hoverClass);
    if (this.options.hoverColor) this.colorbar.setStyle({ background: this.options.color });
  },

  onMouseover: function(event) {
    var element = event.element();
    if (!element.rating) return;

    this.setBarPosition(this.colorbar, element.rating, (this.options.effect && this.options.effect.mouseover));
    if(!this.hovered && this.options.hoverClass) this.hover.addClassName(this.options.hoverClass);
    this.hovered = true;
    if (this.options.hoverColor) this.colorbar.setStyle({ background: this.options.hoverColor });
  }
});